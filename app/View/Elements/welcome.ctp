<div class="pull-right">

<? if ($user) : ?>

    Hello <?= $user['name'] ?><br/>
    <a href="/users/edit/<?= $user['id'] ?>">Your Profile</a> | <a href="/users/logout">Logout</a>

<? else: ?>

    <form class="form-inline" action="/users/login" method="POST">
        <input type="text" class="input-small" placeholder="Username" name="data[User][username]">
        <input type="password" class="input-small" placeholder="Password" name="data[User][password]">
        <label class="checkbox">
            <input type="checkbox"> Remember me
        </label>
        <button type="submit" class="btn">Login</button>
    </form>

<? endif; ?>

</div>