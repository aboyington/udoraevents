<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php echo lang_check('Message from real-estate web'); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="padding: 10px">
<a href="<?php echo site_url(); ?>"><img title="website logo" alt="logo png" src="<?php echo base_url('adminudora-assets/img/stamp.png');?>" /></a>
<p><?php _l('Your property on hold message'); ?></p>
<?php foreach($data as $key=>$value): ?>
<?php if(!empty($value)): ?>
    <p>
    <strong><?php echo lang_check($key); ?>:</strong> <?php echo $value; ?><br />
    </p>
<?php endif; ?>
<?php endforeach; ?>
</body>
</html>




