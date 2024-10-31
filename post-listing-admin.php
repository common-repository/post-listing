<?php
$ripath = plugins_url( '', __FILE__);
$args = array(  'public'   => true /*,   '_builtin' => false*/ );
$ripost_types = get_post_types($args);


?>
<div class="wrap ripladmin">
<form action="" method="post">
	<input type="hidden" name="_ricplanonce" value="<?php echo wp_create_nonce( 'ripla-nonce' ); ?>" />
	<p><b>Type : </b>
	<select name="ptype"><?php
		foreach ( $ripost_types as $k=>$post_type ) {
			$sel = '';
			//if($k==$ritype){ $sel = 'selected="selected"'; }
		   echo '<option '.$sel.' value="'.$k.'">'.$post_type.'</option>';
		}
  ?></select>
  </p>
  <p><b>Category : </b>
  	<select name="pcat"> <option value="">All</option><?php
		$catsapp = get_categories( array(  'orderby'    => 'name',  'show_count' => true,  /*'child_of'  => $cid1,*/ 'hide_empty' => 0   ) );
		//category-image
		foreach($catsapp as $cat){
			echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';
		}
  ?></select>
  </p>
  <p><b>Tags : </b>
  	<select name="ptag"> <option value="">All</option><?php
		$catsapp = get_tags( array(  'orderby'    => 'name',  'show_count' => true,  /*'child_of'  => $cid1,*/ 'hide_empty' => 0   ) );
		//category-image
		foreach($catsapp as $cat){
			echo '<option value="'.$cat->term_id.'">'.$cat->name.'</option>';
		}
  ?></select>
  </p>
  <p><b>Order By : </b>
  <?php $ordby = array('date'=>'Date', 'title'=>'Title', 'rand'=>'Random'); ?>
  	<select name="ordby"><?php
    	foreach($ordby as $k=>$val){
			echo '<option value="'.$k.'">'.$val.'</option>';
		}  ?>
    </select>
  </p>
  <p><b>Order : </b>
  <?php $ordby = array('asc'=>'Asc', 'desc'=>'Desc'); ?>
  	<select name="ord"><?php
    	foreach($ordby as $k=>$val){
			echo '<option value="'.$k.'">'.$val.'</option>';
		}  ?>
    </select>
  </p>
  <p><b>No. of Posts : </b>
  	<input type="number" name="count" value="10" />
  </p>
  <p><b>Offset : </b>
  	<input type="number" name="offset" value="1" />
  </p>
  <p><i>OUTPUT</i></p>
  <p><b>Template : </b>
  <?php $ordby = array('t1'=>'Template 1', 't2'=>'Template 2'); ?>
  	<select name="op"><?php
    	foreach($ordby as $k=>$val){
			echo '<option value="'.$k.'">'.$val.'</option>';
		}  ?>
    </select>
  </p>
  
  <p style="display:none"><b>Hide : </b>
  	<span>
      <input type="checkbox" name="oph[]" value="title" />Title <br />
      <input type="checkbox" name="oph[]" value="thumb" />Thumbnail <br />
      <input type="checkbox" name="oph[]" value="Excerpt" />Excerpt 
      <input type="checkbox" name="oph[]" value="date" />Date <br />
        <input type="checkbox" name="oph[]" value="author" />Author <br />
    </span>
  </p>
  <p>
  	<b>Excerpt Size</b><input name="exrptsize" type="number" value="50" /> 
  </p>
  
  <p><input type="submit" value="Generate Shortcode" /></p>
</form>
<?php
//postList type='post' cat='23' tag='24' ordby='date' ord='asc' count='10' offset='0' temp='t1' hide='date,author' exrpt='50';

if(isset($_POST['_ricplanonce']) && wp_verify_nonce( $_POST['_ricplanonce'], 'ripla-nonce' )){
	if(isset($_POST['ptype'])){ $h = '';
		if($_POST['oph']){ $h = implode(',' , $_POST['oph']); }
		$type = ''; $cat = ''; $tag = ''; $ob = ''; $ord = ''; $count = ''; $os = ''; $tmp = ''; $exp = '';
		if( strlen($_POST['ptype']) < 30 ){ $type =  sanitize_text_field($_POST['ptype']); }
		if(strlen($_POST['pcat']) < 6 && $_POST['pcat']!=''){ $cat = intval($_POST['pcat']); }
		if(strlen($_POST['ptag']) < 6 && $_POST['ptag']!=''){ $tag = intval($_POST['ptag']); }
		if($_POST['ordby']=='date' || $_POST['ordby']=='title' || $_POST['ordby']=='rand'){ $ob = sanitize_text_field($_POST['ordby']); }
		if($_POST['ord']=='asc' || $_POST['ord']=='desc' || $_POST['ord']=='rand'){ $ord = sanitize_text_field($_POST['ord']); }
		if(strlen($_POST['count'])<3){ $count = intval($_POST['count']); }
		if(strlen($_POST['offset'])<8 && intval($_POST['offset'])>0){ $os = intval($_POST['offset']); }
		if($_POST['op']=='t1' || $_POST['op']=='t2'){ $tmp = sanitize_text_field($_POST['op']); }
		if(strlen($_POST['exrptsize'])<3){ $exp = intval($_POST['exrptsize']); }
		
		
		echo "[postList type='".$type."' cat='".$cat."' tag='".$tag."' ordby='".$ob."' ord='".$ord."' count='".$count."' offset='".$os."' temp='".$tmp."' exrpt='".$exp."']";
	}
}
?>


  <div class="template">
    	<h2>Template 1</h2>
        <ul id="ripl_template1">
        	<li> 
            	<span><img src="<?php echo $ripath; ?>/img/image.jpg" /></span>
            	<h2><a href="#"> Post title </a></h2>
                <label>Posted by : Author | Posted on : 11-01-2016</label>
                <div class="riexcerpt">
               	  <p>This is post short description.</p>
                </div>
          </li>
        </ul>
    </div>


    <div class="template">
    	<h2>Template 2</h2>
      <ul id="ripl_template2">
        	<li> 
            	<span><img src="<?php echo $ripath; ?>/img/image.jpg" /></span>
            	<div class="postdesri">
                    <h2><a href="#"> Post title </a></h2>
                    <label>Posted by : Author | Posted on : 11-01-2016</label>
                    <div class="riexcerpt">
                        <p>This is post short description.</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>