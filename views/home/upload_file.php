<script type="text/javascript">
  function checkForFile() {
    if (document.getElementById('file').value) {
      return true;
    }
    document.getElementById('errMsg').style.display = '';
    return false;
  }
</script>
<form action="<?= $url ?>?nexturl=<?= base_url() ?>connections/youtube/success" method="post" enctype="multipart/form-data" onsubmit="return checkForFile();">
  <input id="file" type="file" name="file"/>
  <div id="errMsg" style="display:none;color:red">
    You need to specify a file.
  </div>
  <input type="hidden" name="token" value="<?= $token ?>"/>
  <input type="submit" value="go" />
</form>