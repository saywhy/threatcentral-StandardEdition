<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>


</div>

<script type="text/javascript">
	window.onload = function(){
		$.ajax({ 
    		url: "/site/test?page=3",
    		type: "post",
    		dataType: "json",
    		success: function(data){

				console.log(data)
			}
		});

		var filelist = {
			'm1':{
				name:'gaolei'
			}
		}
		alertlist = [];
		alertlist.push(filelist['m1']);
		alertlist[0]['name'] = '磊哥真帅';
		console.log(alertlist)
		console.log(filelist)	
	}
	// for(var i=1;i<10;i++)
 //    {
 //        history.replaceState({page:i}, "page", "?page="+i);
 //    }
	
</script>
