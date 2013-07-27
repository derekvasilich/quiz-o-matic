<? $action = $this->request->action; ?>

<div class="alert alert-error">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
    <? 
        if ($message === 'You are not authorized to access that location.' && $action === 'login')
            echo "You have been logged out due to inactivity.";
        else
            echo h($message);
    ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".alert-error").fadeIn(1000).delay(5000).fadeOut(2000);
	});
</script>