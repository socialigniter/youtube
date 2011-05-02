<form action="<?= base_url() ?>youtube/connections/upload" method="post" enctype="multipart/form-data">
  Title<br>
  <input type="text" name="title" value="" size="32"><br>
  Description<br>
  <input type="text" name="description" value="" size="32"><br>
  Category<br>
  <input type="text" name="category" value="" size="22"><br>
  Tags<br>
  <input type="text" name="tags" value="" size="22"><br>

  <input type="hidden" name="token" value="<?= $oauth_token ?>"/><br>
  <input type="submit" value="go" />
</form>