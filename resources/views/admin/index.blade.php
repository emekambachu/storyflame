<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>
<div id="admin-app">
    <router-view
        :csrf_token="{{ json_encode(csrf_token()) }}"
    ></router-view>
</div>
</body>
@vite('resources/admin-app.ts');
<script>
    window.authUser = @json(auth()->user(), JSON_THROW_ON_ERROR);
</script>
</html>
