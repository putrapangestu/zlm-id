# Implementation Order — ZLM.ID Transaction Module (TRX)

> File ini berisi urutan implementasi global untuk modul TRX-1 sampai TRX-10.
> Dibuat oleh Planner Agent berdasarkan analisis dependency graph.

---

## Dependency Graph

```
TRX-1 (Migration)
  │
  ├──────────────────┬──────────────────┬──────────────────┬──────────────────┐
  │                  │                  │                  │                  │
  ▼                  ▼                  ▼                  ▼                  ▼
TRX-2             TRX-2B             TRX-4             TRX-6             TRX-8
(Xendit)         (RajaOngkir)      (Upload Proof)   (Admin Trans)     (Dashboard)
  │                  │                                  │                  │
  │                  │                                  ▼                  │
  │                  │                               TRX-9                │
  │                  │                              (Sidebar)             │
  ├──────┬───────────┘                                                    │
  │      │                                                                │
  ▼      ▼                                                                │
TRX-5  TRX-3                                                             │
(Webhook)(Checkout)                                                       │
          │                                                                │
          ▼                                                                │
        TRX-10 ← ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ─ ┘
        (Tax Settings) — touches OrderController, TransactionController,
                         checkout view, admin sidebar
```

---

## Fase Implementasi

### FASE 1: Foundation (Sequential — WAJIB duluan)
| Urutan | Modul | Effort | Dikerjakan | Keterangan |
|--------|-------|--------|------------|------------|
| 1 | **TRX-1** (Migration) | Small | Solo | Semua modul lain butuh kolom ini |

### FASE 2: Services + Parallel Work (Parallel — bisa barengan)
| Urutan | Modul | Effort | Dikerjakan | Keterangan |
|--------|-------|--------|------------|------------|
| 2a | **TRX-2** (Xendit Service) | Medium | Parallel | Hanya butuh TRX-1 |
| 2b | **TRX-2B** (RajaOngkir Service) | Medium | Parallel | Hanya butuh TRX-1 |
| 2c | **TRX-4** (Upload Proof) | Small | Parallel | Hanya butuh TRX-1 |
| 2d | **TRX-6** (Admin Transactions) | Large | Parallel | Hanya butuh TRX-1 (Xendit opsional) |
| 2e | **TRX-8** (Dashboard Stats) | Small | Parallel | Hanya butuh TRX-1 |
| 2f | **TRX-10** (Tax Settings) | Medium | Parallel | Independent table, touches banyak file |

### FASE 3: Integration (Sequential — butuh services ready)
| Urutan | Modul | Effort | Dikerjakan | Keterangan |
|--------|-------|--------|------------|------------|
| 3a | **TRX-5** (Webhook) | Small | Setelah TRX-2 | Butuh XenditService |
| 3b | **TRX-3** (Checkout) | Large | Setelah TRX-2 + 2B + 10 | Butuh semua services + tax config |
| 3c | **TRX-7** (User History) | Medium | Setelah TRX-1 + 4 | Butuh migration + upload proof |

### FASE 4: UI Polish (Sequential — butuh routes ready)
| Urutan | Modul | Effort | Dikerjakan | Keterangan |
|--------|-------|--------|------------|------------|
| 4a | **TRX-9** (Sidebar) | Tiny | Setelah TRX-6 | Butuh route admin.transactions ada |

---

## Ringkasan

| Fase | Total Effort | Waktu Estimasi |
|------|-------------|----------------|
| FASE 1 — Foundation | 1 Small | ~1 jam |
| FASE 2 — Services | 2 Medium + 1 Large + 2 Small + 1 Medium | ~6-8 jam (parallel) |
| FASE 3 — Integration | 1 Small + 1 Large + 1 Medium | ~4-5 jam |
| FASE 4 — UI Polish | 1 Tiny | ~30 menit |
| **Total** | **10 modul** | **~12-15 jam** |

## Catatan Penting

1. **TRX-10 (Tax Settings) touches many files**: Modul ini mengubah OrderController, TransactionController, checkout view, dan admin sidebar. Sebaiknya dikerjakan setelah TRX-3 selesai (agar tidak conflict saat merge), atau dikerjakan di FASE 2 tapi di-merge setelah TRX-3.

2. **TRX-3 (Checkout) is the biggest module**: Ini modul paling kompleks karena mengubah major bagian checkout + integrasi dengan 3 service berbeda. Prioritaskan testing yang ketat.

3. **TRX-6 (Admin Transactions) is also large**: Full CRUD + 3 view baru + Xendit integration. Bisa dikerjakan parallel dengan services karena dependency hanya TRX-1.

4. **TRX-4 (Upload Proof) small & independent**: Bisa dikerjakan kapan saja setelah TRX-1.

5. **Conflict Potential**: TRX-3, TRX-6, TRX-7, TRX-10 semua memodifikasi `OrderController.php` dan `resources/views/orders/checkout.blade.php`. Hati-hati saat merge — lebih baik sequential untuk file yang sama.

## Recommended Execution Strategy

### Option A: Maximum Parallel (Tim besar)
```
Week 1: TRX-1 (1 org) + TRX-10 (1 org)
Week 2: TRX-2 (1 org) + TRX-2B (1 org) + TRX-4 (1 org) + TRX-6 (1 org) + TRX-8 (1 org)
Week 3: TRX-5 (1 org) + TRX-7 (1 org) + TRX-3 (2 org)
Week 4: TRX-9 (tiny) + Testing + Bugfix
```

### Option B: Sequential (1-2 org, recommended)
```
Day 1:  TRX-1 + TRX-2 + TRX-2B
Day 2:  TRX-10 + TRX-4 + TRX-8
Day 3:  TRX-5 + TRX-6
Day 4:  TRX-3
Day 5:  TRX-7 + TRX-9 + Testing
```
