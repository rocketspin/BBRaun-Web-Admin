<?php if(!isset($data) || !isset($page)) exit('<h1 align="center">Variables Data is not define on your controller!</h1>');?>

<?php $this->load->view('includes/header',$data)?>
<?php $this->load->view('pages/'.$page, $data)?>
<?php $this->load->view('includes/footer')?>