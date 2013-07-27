<?  
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$ventisDescription = __d('ventis_title', 'Ventis: Academic & Clinical Management Tools for Teaching Hospitals');
$user = $this->Session->read('Auth.User');
$controller = '';
$action = $this->request->action;

?>
<?= $this->Html->docType('html5') ?>
<html lang="en">
<head>
    <?= $this->Html->charset(); ?>
    <title><?= h($ventisDescription) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/favicon.ico?v=2" />    	
	<?
		echo $this->Html->css('bootstrap.min');
		echo $this->Html->css('bootstrap-responsive.min');
		echo $this->Html->css('font-awesome.min');
		echo $this->Html->css('quizmatic.generic');
                
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
                
		echo $this->Html->script('jquery-1.10.1.min');
	?>
        <!--[if lt IE 9]>
        <?
            echo $this->Html->script('css3-mediaqueries', array('inline' => true));
            echo $this->Html->script('html5shiv', array('inline' => true));            
        ?>
        <![endif]-->
</head>
<body class="<?= Inflector::camelize(h($controller)) ?> <?= h($action) ?> splash"> 
    <script type="text/javascript">
        $(document).ready(function() {
            $('#flashMessage').alert();
        });
    </script>
    <div class="navbar navbar-fixed-top">
        <div class="container">
            <div id="header">
                <h1>Quiz-o-matic</h1>
                <?= $this->element('welcome', array('user'=>$user)) ?>
            </div>        
            <div id="tagline">
                <div class="container-fixed">Build a Quiz, Take a Quiz, Share a Quiz.</div>
            </div>  
            <div class="navbar-inner">
                <div class="container-fixed">
                    <button data-target=".nav-collapse" data-toggle="collapse" class="btn btn-navbar" type="button">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li><?= $this->Html->link('Quizzes by Category', array('controller'=>'quizzes', 'action'=>'index')) ?></li>
                            <li><?= $this->Html->link('Build a Quiz', array('controller'=>'quizzes', 'action'=>'index')) ?></li>
                            <li><?= $this->Html->link('Your Quizzes', array('controller'=>'quizzes', 'action'=>'index')) ?></li>
                            <li><?= $this->Html->link('Favorite Quizzes', array('controller'=>'quizzes', 'action'=>'index')) ?></li>
                        </ul>
                    </div>                
                </div>
            </div>
        </div>
    </div>
    <div class="content container">
        <div class="row-fixed">
            <div class="span12">
                <?
                    echo $this->Session->flash(); 
                    echo $this->Session->flash('auth'); 
                ?>
                <div class="widget">
                    <?= $this->fetch('content'); ?>
                </div>
            </div>
        </div>
        <div id="footer" class="row pull-right">
            <span>Copyright &copy; 2013 Quiz-o-matic</span> 
        </div>
    </div>
    <?= $this->Html->script('bootstrap'); ?>
</body>
</html>
