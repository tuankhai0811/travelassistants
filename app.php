<p style="font-size:20">Add Province</p>
<form action="http://localhost/TravelAssistants/public/Province" method="POST">
	<input type="text" name="id" placeholder="Nhập ID" />
	<br>
	<br>
	<input type="text" name="name" placeholder="Nhập tên tỉnh"/>
	<br>
	<br>
	<input type="submit"/>
	<br>
	<br>
</form>

<p style="font-size: 20">Show Provinces</p>
<form action="http://localhost/TravelAssistants/public/Province" method="GET">
	<input type="submit">
	<br>
	<br>
</form>

<p style="font-size:20">Add Place</p>
<form action="http://localhost/TravelAssistants/public/Place" method="POST">
	<input type="text" name="id" placeholder="Nhập ID(5)" />
	<br>
	<br>
	<input type="text" name="long_name" placeholder="Tên địa danh"/>
	<br>
	<br>
	<input type="text" name="province_id" placeholder="ID Province"/>
	<br>
	<br>
	<input type="submit"/>
	<br>
	<br>
</form>