<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ananya API Documentation - News/Articles</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        h1 {
            border-bottom: 2px solid #ddd;
            padding-bottom: 15px;
            color: #2c3e50;
        }

        h2 {
            margin-top: 30px;
            color: #16a085;
        }

        h3 {
            color: #2980b9;
        }

        code {
            background: #f0f0f0;
            padding: 2px 5px;
            border-radius: 4px;
            font-family: 'Consolas', 'Monaco', monospace;
            color: #c7254e;
        }

        pre {
            background: #272822;
            color: #f8f8f2;
            padding: 15px;
            border-radius: 6px;
            overflow-x: auto;
            font-family: 'Consolas', 'Monaco', monospace;
        }

        .endpoint {
            background: #e8f6f3;
            padding: 15px;
            border-left: 5px solid #1abc9c;
            margin-bottom: 20px;
        }

        .method {
            font-weight: bold;
            color: #fff;
            background: #2ecc71;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85em;
        }

        .url {
            font-weight: bold;
            color: #2c3e50;
            margin-left: 10px;
        }

        .param-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }

        .param-table th,
        .param-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .param-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>API Documentation for Mobile News/Articles</h1>
        <p>This document describes the API endpoint used by the Android Mobile Application to fetch the main dashboard
            news and article feeds.</p>

        <div class="endpoint">
            <span class="method">GET</span> <span class="url">/news/topic24</span>
        </div>

        <h3>Description</h3>
        <p>Retrieves categorized lists of articles to be displayed on the mobile app dashboard (e.g., Hot News, Reviews,
            Phone Numbers, Tabian, etc.).</p>

        <h3>Response Format (JSON)</h3>
        <p>The API returns a JSON object containing several arrays of <code>NewsHeadline</code> objects, keyed by
            category.</p>

        <pre>
{
  "news_hot": [ ... ],       // List of Hot News (5-7 Items)
  "news_feedback": [ ... ],  // List of Review/Feedback Articles
  "news_phonenum": [ ... ],  // Phone Number Articles
  "news_namesur": [ ... ],   // Name-Surname Articles
  "news_tabian": [ ... ],    // License Plate Articles
  "news_homenum": [ ... ],   // House Number Articles
  "news_concept": [ ... ]    // Concept/Knowledge Articles
}
    </pre>

        <h3>NewsHeadline Object Structure</h3>
        <p>Each item in the arrays above follows this structure:</p>

        <table class="param-table">
            <thead>
                <tr>
                    <th>Field Name (JSON Key)</th>
                    <th>Type</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><code>newsid</code></td>
                    <td>String/Int</td>
                    <td>Unique ID of the article.</td>
                </tr>
                <tr>
                    <td><code>news_headline</code></td>
                    <td>String</td>
                    <td>The main title of the article.</td>
                </tr>
                <tr>
                    <td><code>news_title_short</code></td>
                    <td>String</td>
                    <td>(Optional) A shorter title for grid displays.</td>
                </tr>
                <tr>
                    <td><code>news_desc</code></td>
                    <td>String</td>
                    <td>A brief excerpt or summary of the article.</td>
                </tr>
                <tr>
                    <td><code>news_pic_header</code></td>
                    <td>String</td>
                    <td>Full absolute URL to the cover image.</td>
                </tr>
                <tr>
                    <td><code>news_date</code></td>
                    <td>String</td>
                    <td>Publication date (e.g., YYYY-MM-DD HH:mm:ss).</td>
                </tr>
                <tr>
                    <td><code>category</code></td>
                    <td>String</td>
                    <td>The category name of the article.</td>
                </tr>
            </tbody>
        </table>

        <h3>Example Response</h3>
        <pre>
{
  "news_hot": [
    {
      "newsid": "101",
      "news_headline": "ศาสตร์ตัวเลข กับคุณวิกรม กรมดิษฐ์",
      "news_title_short": "คุณวิกรมและตัวเลข",
      "news_desc": "นักธุรกิจเยี่ยมยุทธ์ นักขายมือหนึ่ง...",
      "news_pic_header": "https://api.yourdomain.com/uploads/vikrom.jpg",
      "news_date": "2024-01-24 10:30:00",
      "category": "Hot News"
    }
  ],
  "news_feedback": [],
  "news_phonenum": [],
  "news_namesur": [],
  "news_tabian": [],
  "news_homenum": [],
  "news_concept": []
}
    </pre>
    </div>

</body>

</html>