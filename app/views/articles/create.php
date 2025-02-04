<!DOCTYPE html>
<html>
<head>
    <title>Create Article</title>
</head>
<body>
    <h1>Create Article</h1>
    <form method="POST" action="/articles/create">
        <label for="title">Title:</label>
        <input type="text" name="title" required>
        <br>
        <label for="content">Content:</label>
        <textarea name="content" required></textarea>
        <br>
        <button type="submit">Create</button>
    </form>
</body>
</html>