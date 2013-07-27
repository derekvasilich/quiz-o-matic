<?
    $class = '';
    if (preg_match('<br[/]*>', $message))
        $class = 'high';
?>

<div class="alert alert-success <?= $class; ?>">
    <a class="close" data-dismiss="alert" href="#">&times;</a>
    <?= h($message); ?>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(".alert-success").fadeIn(1000).delay(5000).fadeOut(2000);
	});
</script>