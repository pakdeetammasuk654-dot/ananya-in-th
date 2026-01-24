## 2024-07-15 - N+1 Query in `dressColor`

**Learning:** Identified a classic N+1 query problem in `app/Managers/userx/UserController.php`. The `dressColor` function was iterating through a list of day IDs and executing a separate database query for each ID inside a loop. This is a common and significant performance bottleneck.

**Action:** Refactored the loop to use a single `IN` clause, fetching all required data in one database roundtrip. This is the standard solution for this anti-pattern and should be my default approach whenever I see queries inside loops.