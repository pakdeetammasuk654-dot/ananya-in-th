## 2025-12-21 - [N+1 Query in UserController::dressColor]
**Learning:** The `dressColor` method was executing a database query for every character in the input string. This pattern is a major performance bottleneck due to multiple database roundtrips. Even with a small dataset, the cumulative latency of these queries is significant.
**Action:** Replace iterative queries with a single batch fetch using `WHERE ... IN (...)`. Use an associative array (hash map) to reconstruct the results in the original order and handle duplicates in $O(N)$ time.

## 2025-12-21 - [Mocking PDO for Verification]
**Learning:** In environments without a live database, performance optimizations can still be verified by mocking the `PDO` and `PDOStatement` objects. This allows for counting the number of queries and verifying that the refactored logic produces identical output to the original implementation.
**Action:** Use simulation scripts to quantitatively prove query reduction and ensure zero regressions in output data.
