

	<select id = "saletype" onchange = "ShowHideDiv()" style="width:150px;">
<option value="Y">Select Type</option>
<option value="Y">Type 2</option>
<option value="User">User 3</option>
<option value="Module">Module</option>
</select>


<script type="text/javascript">
    function ShowHideDiv() 
    {
    var saletype = document.getElementById("saletype");
    refocfno.style.display = saletype.value == "User" ? "block" : "none";
	purchasedate.style.display = saletype.value == "Module" ? "block" : "none";
    }
</script>


 <div id="dvPassport" style="display: none">
          <form action="viewdata.php" method="POST">
        Enter Data:
        <input type="text" id="txtPassportNumber" name="data" />
        <br>
        <input type="submit" value="Search" name="submit" 
     style="float:right;width:150px;margin-top:10px;">
    </form>
    </div>
    <div id="refocfno" style="display: none">
        <form action="viewdata.php" method="POST">
        Ref ocf No
        <input type="number" id="txtPassportNumber" name="data"  />
         <br>
        <input type="submit" value="Search" name="submit" 
   style="float:right;width:150px;margin-top:10px;">
    </form>
    </div>
    <div id="purchasedate" style="display: none">
        <form action="viewdata.php" method="POST">
        Enter Data:
        <input type="date" id="txtPassportNumber" name="data"  />
         <br>
        <input type="submit" value="Search" name="submit" 
       style="float:right;width:150px;margin-top:10px;">
    </form>
    </div>