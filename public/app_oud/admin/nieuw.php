<?
require_once('../session.php');
	require_once('admin_inc/toernooi.class.php');

//check of er een submit heeft plaatsgevonden
if (isset($_POST['nieuw']))
{
	if (!(empty($_POST['naam']) OR empty($_POST['datum']) OR empty($_POST['teams']) OR empty($_POST['poules']) OR empty($_POST['velden']) OR empty($_POST['aanvang']) OR empty($_POST['duur'])))
	{
		$toernooi = new Toernooi();
		$toernooi_id=$toernooi->maakToernooi($_POST['naam'],$_POST['datum'],$_POST['teams'],$_POST['poules'],$_POST['velden'],$_POST['aanvang'],$_POST['duur'],$_POST['comp']);
		header("Location: wedstrschema.php?toernooiid=".$toernooi_id);
	}
	else
	{
		include('menu.php');
		?>
		<div id="box2">
			<form method="post" action="nieuw.php">
			<TABLE>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Naam Toernooi </TD>
			<TD align=left class="zonder"><input type="text" size="30" name="naam" value="<? echo $_POST['naam'] ?>" ></TD>
			</TR>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Datum </TD>
			<TD align=left class="zonder"><input type="date" size="8" name="datum" value="<? echo $_POST['datum'] ?>" ></TD>
			</TR>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Aantal Teams </TD>
			<TD align=left class="zonder"><input type="number" size="2" name="teams" value="<? echo $_POST['teams'] ?>"></TD>
			</TR>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Aantal poules </TD>
			<TD align=left class="zonder"><input type="number" size="2" name="poules" value="<? echo $_POST['poules'] ?>"></TD>
			</TR>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Aantal velden </TD>
			<TD align=left class="zonder"><input type="number" size="2" name="velden" value="<? echo $_POST['velden'] ?>"></TD>
			</TR>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Aanvangstijd </TD>
			<TD align=left class="zonder"><input type="time" size="6" name="aanvang" value="<? echo $_POST['aanvang'] ?>"></TD>
			</TR>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Wedstrijdduur (minuten) </TD>
			<TD align=left class="zonder"><input type="number" size="2" name="duur" value="<? echo $_POST['duur'] ?>"></TD>
			</TR>
			<TR>
			<TD HEIGHT="40" align=right class="zonder">Hele of halve competitie </TD>
			<TD align=left class="zonder"><input type="radio" name="comp" value="1"> hele&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="comp" value="0" checked> halve</TD>
			</TR>
			<TR>
			<TD HEIGHT="40" class="zonder"></TD>
			<TD align=left class="zonder"><input type="Submit" name="nieuw" value="Verzend"><font color=red> u moet alle velden invullen!</font></TD>
			</TR>
			</TABLE>
			</form>
		</div>
		<?
	}
}
else
{
	include('menu.php');
	?>
	<div id="box2">
		<form method="post" action="nieuw.php">
		<TABLE>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Naam Toernooi </TD>
		<TD align=left class="zonder"><input type="text" size="30" name="naam" value="" ></TD>
		</TR>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Datum </TD>
		<TD align=left class="zonder"><input type="date" size="8" name="datum" value="" ></TD>
		</TR>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Aantal Teams </TD>
		<TD align=left class="zonder"><input type="number" size="2" name="teams" value=""></TD>
		</TR>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Aantal poules </TD>
		<TD align=left class="zonder"><input type="number" size="2" name="poules" value=""></TD>
		</TR>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Aantal velden </TD>
		<TD align=left class="zonder"><input type="number" size="2" name="velden" value=""></TD>
		</TR>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Aanvangstijd </TD>
		<TD align=left class="zonder"><input type="time" size="6" name="aanvang" value=""></TD>
		</TR>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Wedstrijdduur (minuten) </TD>
		<TD align=left class="zonder"><input type="number" size="2" name="duur" value=""></TD>
		</TR>
		<TR>
		<TD HEIGHT="40" align=right class="zonder">Hele of halve competitie </TD>
		<TD align=left class="zonder"><input type="radio" name="comp" value="1"> hele&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="comp" value="0" checked> halve</TD>
		</TR>
		<TR>
		<TD HEIGHT="40" class="zonder"></TD>
		<TD align=left class="zonder"><input type="Submit" name="nieuw" value="Verzend"></TD>
		</TR>
		</TABLE>
		</form>
	</div>
<?
}
//include('footer.php');
?>
    </div><!-- afsluiting boxContainer zit in menu.php-->
</div><!-- afsluiting boxContainerContainer zit in menu.php-->
</body>
</html>

