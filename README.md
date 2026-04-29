Initial Website Information:
- This website is a laptop sales website
- There are two main users: admin and buyer
- Users can only access the landing page and user pages (purchase history, shopping cart, profile)
- Admins can access everything, including the admin dashboard
- There will be two main displays: the landing page and the admin view
- The main colors of this website are #DF5E1D and #363230
- Create a design that meets international UI/UX standards
- Create clean and modular coding

Landing Page Details
- Home page, containing several laptop recommendations, testimonials, store information, and articles
- Laptop Search page, containing a list of available laptops, equipped with a filter feature based on budget range and laptop specifications
- Compare page, where you can compare laptops with other laptops when selecting a laptop
- Detail page, containing details about the currently selected laptop and recommendations for similar laptops

Admin Pages Details
- Product Management, this pages for CRUD laptops, the field is (multiple images, name, brand, type, description, price, processor, RAM, storage, graphic, display, battery, weight, minus)
- Article Management, this page for CRUD articles, the field is (name, description with WYSWYG, author, date)
- Transaction Page, this page is list transaction
- Tracking management, this page to update current location shiping product, this page relate to transaction list
- User management, this page for CRUD users
- Report, the submenu is laba rugi, pembelian, statistik barang
- User Profile, this page for CRUD users

struktur database
#table products
- id
- name
- brand
- type
- description
- price
- processor
- ram
- storage
- graphic
- display
- battery
- weight
- minus

#table articles
- id
- name
- description
- author
- date

#table transactions
- id
- user_id
- total_price
- payment_method
- payment_status
- status
- created_at
- updated_at

#table transaction_items
- id
- transaction_id
- product_id
- quantity
- price
- total_price

#table images
- id
- model_id
- model_type
- image

