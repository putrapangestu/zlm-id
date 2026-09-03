/**
 * IndexedDB Database Layer for ZLM.ID Offline-First POS
 */
const PosDB = (function () {
    const DB_NAME = 'zlm_pos_db';
    const DB_VERSION = 1;
    let dbInstance = null;

    function open() {
        return new Promise((resolve, reject) => {
            if (dbInstance) {
                return resolve(dbInstance);
            }

            const request = indexedDB.open(DB_NAME, DB_VERSION);

            request.onupgradeneeded = function (e) {
                const db = e.target.result;

                // Products Store
                if (!db.objectStoreNames.contains('products')) {
                    const prodStore = db.createObjectStore('products', { keyPath: 'id' });
                    prodStore.createIndex('name', 'name', { unique: false });
                    prodStore.createIndex('brand', 'brand', { unique: false });
                }

                // QC Units with SKU Store
                if (!db.objectStoreNames.contains('qc_units')) {
                    const qcStore = db.createObjectStore('qc_units', { keyPath: 'id' });
                    qcStore.createIndex('sku', 'sku', { unique: true });
                }

                // Categories Store
                if (!db.objectStoreNames.contains('categories')) {
                    db.createObjectStore('categories', { keyPath: 'id' });
                }

                // Members Store
                if (!db.objectStoreNames.contains('members')) {
                    const memberStore = db.createObjectStore('members', { keyPath: 'id' });
                    memberStore.createIndex('phone', 'phone', { unique: false });
                    memberStore.createIndex('member_number', 'member_number', { unique: false });
                }

                // Offline Completed Orders
                if (!db.objectStoreNames.contains('offline_orders')) {
                    db.createObjectStore('offline_orders', { keyPath: 'client_order_uuid' });
                }

                // Sync Queue (Pending Orders to push to Server)
                if (!db.objectStoreNames.contains('sync_queue')) {
                    db.createObjectStore('sync_queue', { keyPath: 'client_order_uuid' });
                }
            };

            request.onsuccess = function (e) {
                dbInstance = e.target.result;
                resolve(dbInstance);
            };

            request.onerror = function (e) {
                console.error('[PosDB] Error opening IndexedDB:', e);
                reject(e);
            };
        });
    }

    async function setAll(storeName, items) {
        const db = await open();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            store.clear();
            items.forEach(item => store.put(item));
            tx.oncomplete = () => resolve(true);
            tx.onerror = (e) => reject(e);
        });
    }

    async function getAll(storeName) {
        const db = await open();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const request = store.getAll();
            request.onsuccess = () => resolve(request.result || []);
            request.onerror = (e) => reject(e);
        });
    }

    async function get(storeName, key) {
        const db = await open();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const request = store.get(key);
            request.onsuccess = () => resolve(request.result);
            request.onerror = (e) => reject(e);
        });
    }

    async function put(storeName, item) {
        const db = await open();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            store.put(item);
            tx.oncomplete = () => resolve(true);
            tx.onerror = (e) => reject(e);
        });
    }

    async function remove(storeName, key) {
        const db = await open();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            store.delete(key);
            tx.oncomplete = () => resolve(true);
            tx.onerror = (e) => reject(e);
        });
    }

    async function findBySku(sku) {
        const db = await open();
        return new Promise((resolve) => {
            const tx = db.transaction('qc_units', 'readonly');
            const store = tx.objectStore('qc_units');
            const index = store.index('sku');
            const req = index.get(sku);
            req.onsuccess = () => resolve(req.result);
            req.onerror = () => resolve(null);
        });
    }

    return {
        open,
        setAll,
        getAll,
        get,
        put,
        remove,
        findBySku
    };
})();
