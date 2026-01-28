# Bolt's Journal

This journal contains critical learnings about performance optimizations.

## 2026-01-28 - N+1 Query in `dressColor`
**Learning:** Identified a classic N+1 query problem in `app/Managers/userx/UserController.php` in the `dressColor` function. The function was iterating over a string and executing a database query for each character. This is a common and significant performance anti-pattern.
**Action:** Always look for loops that contain database queries. Consolidate these queries into a single query using an `IN` clause or a similar mechanism. This will be my primary optimization target.
