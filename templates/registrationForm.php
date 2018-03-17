<?php if(!$this) die();?>

<div id="registrationFormDiv" class="forms">
  <h2> Wprowadź dane rejestracyjne: </h2>
  <form name = "regForm"
        action = "index.php?action=registerUser"
        method = "post"
  >
  <table>
    <?php foreach($formData as $input): ?>
      <tr>
        <td class='col1st'><?=$input->description?>:</td>
        <td class='col2nd'>
          <?=$input->getInputHTML()?>
        </td>
      </tr>
    <?php endforeach;?>
    <tr>
      <td colspan="2" class="colmerged">
        <input type="submit" value="Rejestracja">
      </td>
    </tr>
  </table>
  </form>
</div>