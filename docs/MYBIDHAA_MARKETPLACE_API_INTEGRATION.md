# MyBidhaa Marketplace API — Integration Plan

This document outlines **actionable steps** to integrate the [MyBidhaa Products API](https://mybidhaa.com/api) so the school can assign marketplace items to parents (via the existing “Assign items to student” flow).

---

## 1. Current State

- **Product search** is simulated: `AssignItemsToStudentController::searchProducts()` reads from `storage/app/mybidhaa_products.json`.
- **Assign flow** already exists: admin selects products → selects student → assigns; creates `SchoolRecommendedItem` and `ParentRecommendedItem` with `product_link`, `image_url`, etc.
- **Frontend** expects products with: `id`, `name`, `category`, `price`, `description`, `image_url` (optional `currency`).
- **Laravel** stack with Guzzle available; no MyBidhaa API client yet.

---

## 2. API Summary (from documentation)

| Area | Details |
|------|--------|
| **Public base** | `https://mybidhaa.com/api/v1/ecommerce` |
| **Auth base** | `https://mybidhaa.com/api/v1/auth` |
| **Products (no auth)** | `GET /api/v1/ecommerce/products?keyword=...&per_page=20&page=1` (max 50 per page) |
| **Single product** | `GET /api/v1/ecommerce/products/{slug}` |
| **Categories** | `GET /api/v1/ecommerce/product-categories` (optional filter) |
| **Headers** | `Accept: application/json` always; `Content-Type: application/json` for POST/PUT/PATCH |
| **Auth** | Bearer token (24h expiry) only needed for cart, wishlist, orders, checkout — **not** for listing products. |
| **Rate limits** | 60 req/min (public); 10 req/min for login. Doc recommends caching product listings ≥ 60 seconds. |

For **assigning items to parents**, only the **public product endpoints** are required. Parent then uses the stored **product link** (e.g. `https://mybidhaa.com/products/{slug}`) to open MyBidhaa and purchase.

---

## 3. Actionable Steps

### Step 1 — Configuration

- Add config keys for the MyBidhaa API (e.g. in `config/services.php` or `.env`):
  - `MYBIDHAA_API_BASE_URL` = `https://mybidhaa.com/api/v1`
  - Optional: `MYBIDHAA_PRODUCT_CACHE_TTL` = `60` (seconds) to respect rate limits.
- No API key is required for public product listing.

### Step 2 — MyBidhaa API client / service

- Create a small **HTTP client** that:
  - Uses the base URL from config.
  - Sends `Accept: application/json` on every request.
  - Optionally uses Laravel `Cache::remember()` for product list responses (key + TTL from config) to stay within 60 req/min.
- Implement at least:
  - **List products**: `GET /ecommerce/products` with query params: `keyword`, `per_page` (e.g. 12–24), `page`, optionally `category_id`.
  - **Single product by slug**: `GET /ecommerce/products/{slug}` (optional; for “view details” or fallback).
- Map API response to the shape your app expects:
  - API returns `data[]` with `id`, `slug`, `url`, `name`, `description`, `content`, `price`, `price_formatted`, `image_url`, `store` (e.g. store name), etc.
  - Your UI expects: `id`, `name`, `category`, `price`, `description`, `image_url`, optionally `currency`.
  - Suggested mapping:
    - `id` → use `slug` (string) for stable, shareable links; or keep numeric `id` if you prefer.
    - `name`, `description`, `price`, `image_url` → direct.
    - `category` → from category API later, or derive from `store.name` / leave empty for now.
    - `product_link` / link for parent → use API’s `url` (e.g. `https://mybidhaa.com/products/first-aid-box-medium`).

### Step 3 — Replace JSON search with live API in `AssignItemsToStudentController`

- In `searchProducts()`:
  - Remove (or feature-flag) the current logic that reads from `mybidhaa_products.json`.
  - Call the new MyBidhaa client: list products with `keyword` = search query, `per_page` = 20 (or 24).
  - Map each product to the format the frontend already uses: `id`, `name`, `category`, `price`, `description`, `image_url`, and `url` (or equivalent) for `product_link`.
  - Return `['products' => $mappedProducts]` so the existing Blade/JS keeps working.
- When creating `SchoolRecommendedItem`, set:
  - `product_link` = MyBidhaa product URL from the API (e.g. `url` field).
  - Keep storing `image_url`, `price`, `description`, etc., from the API so parent views and reports stay correct even if the link is opened later.

### Step 4 — Error and rate-limit handling

- Treat non-2xx responses as “no results” or a clear error message (e.g. “Unable to load products. Please try again.”).
- If the API returns **429**, respect `Retry-After` (or use exponential backoff) and optionally log; show a user-friendly “Too many requests” message.
- Ensure all API calls use a short cache when possible (e.g. 60 seconds) to reduce 429 risk.

### Step 5 — (Optional) Categories and filters

- Call `GET /api/v1/ecommerce/product-categories` and optionally:
  - Show a category dropdown in the “Assign items to student” UI.
  - Pass `category_id` to the products endpoint for filtered results.
- Improves discovery and aligns with doc recommendation: “Combine keyword + category_id for precise filtered search results.”

### Step 6 — Parent-facing link

- Parent already sees assigned items (e.g. recommended items or procurement dashboard). Ensure each item’s **product link** is the MyBidhaa URL (from API `url`).
- Optionally add a clear “Buy on MyBidhaa” / “View on marketplace” button that opens `product_link` in a new tab.

### Step 7 — (Optional) Health check

- For monitoring or an admin “Integration status” page, call `GET https://mybidhaa.com/api/health` (no auth). If `status === 'ok'`, show “MyBidhaa marketplace connected.”

---

## 4. What you do *not* need for “assign items to parents”

- **Authentication** to MyBidhaa in the school app is not required for listing products or storing links. Parents buy on MyBidhaa in their own session.
- **Cart / checkout / orders** APIs are only needed if you later want “add to parent’s MyBidhaa cart” from the school app (would require parent to link a MyBidhaa account and token storage — out of scope for basic “assign and link”).

---

## 5. Suggested order of implementation

1. Add config and a small MyBidhaa HTTP client with caching.
2. Implement product list (and optionally single product) with response mapping.
3. Replace `searchProducts()` in `AssignItemsToStudentController` with the client; keep same JSON response shape.
4. Test: search → assign → confirm `product_link` and display on parent side.
5. Add error/429 handling and optional category filter + health check.

---

## 6. Files to touch (summary)

| Purpose | File(s) |
|--------|--------|
| Config | `config/services.php` and `.env.example` |
| API client | New `app/Services/MyBidhaaApiService.php` (or similar) |
| Use API in search | `app/Http/Controllers/Admin/StudentInfo/AssignItemsToStudentController.php` → `searchProducts()` |
| Optional: categories in UI | Same controller + `assign_items_to_student.blade.php` |
| Parent link | Already using `product_link`; ensure it’s set from API `url` |

Once you confirm how you’d like to proceed (e.g. “implement Step 1 and 2 only” or “full replacement with caching”), the next step is to add the config and `MyBidhaaApiService`, then wire it into `searchProducts()`.
