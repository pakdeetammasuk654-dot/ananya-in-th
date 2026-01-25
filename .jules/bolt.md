## 2024-07-29 - N+1 Query Anti-Pattern in `dressColor`

**Learning:** Identified a classic N+1 query anti-pattern in `app/Managers/userx/UserController.php` within the `dressColor` method. The code iterates through a list of IDs and performs a separate database query for each, leading to significant database overhead and slow response times, especially with a larger number of colors.

**Action:** Refactor the loop to use a single `SELECT ... WHERE IN (...)` query. This will fetch all the necessary data in one database roundtrip, drastically reducing latency. Always look for loops that contain database queries and consolidate them.
