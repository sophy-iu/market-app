<form class="form" action="/register" method="POST">
    @csrf

    <input type="text" name="name">
    <input type="email" name="email">
    <input type="password" name="password">
    <input type="password" name="password_confirmation">

    <button type="submit">登録する</button>
</form>