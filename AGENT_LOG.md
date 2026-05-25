# AGENT_LOG.md

## 2026-05-22

### Decisions
- **Currency**: Changed all USD ($) to Rupiah (Rp) with format `Rp X.XXX.XXX`
- **Editor**: Chose Trix Editor (by Basecamp) — ringan, tanpa API key, output HTML bersih
- **Default Image**: Using `placehold.co/XXXX/363230/DF5E1D?text=ZLM` dengan brand color
- **Nav Role**: Changed from Laravel Gate `@can('admin')` to Spatie `@role('admin')`
- **Kelebihan/Kekurangan**: Disimpan sebagai HTML dari Trix, dirender dengan `{!! !!}`
- **Admin Route**: Show route added explicitly before resource to avoid conflict
