<?
// start basic list - wedding

// end wedding query
//start callback
	
// end callback
// start contact

// end contact
//start personal
	
// end personal


















		?>
        <h1>Form - Search Submissions</h1>
        <form method="post" action="<?php  echo $site_url; ?>/oos/search-submissions.php" name="getcsvdata">
  <input type="hidden" name="data" value="questionnaire" />
  <div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_from"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_from'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_to"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_to'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <div style="float:left; width:140px; margin:5px;"><strong>Destination</strong></div>
  <div style="float:left;  margin:5px;">
            <select name="destination_id" size="10" style="width:500px;">
      <option value="0" style="font-size:11px;" selected="selected">All Destinations</option>
      <?php  echo $nav_list; ?>
    </select>
          </div>
  <div style="clear:left;"></div>
  <input type="submit" name="action" value="Download CSV" />
</form>
        <p>
        <hr />
        </p>
        <form action="<?php  echo $site_url; ?>/oos/search-submissions.php" method="get">
  <p><strong>Search Surname</strong>
            <input type="text" name="surname" value="" />
            <select name="type" class="inputbox_town" style="width:100px;" >
      <option value="bride">Bride</option>
      <option vlaue="groom">Groom</option>
    </select>
            <input type="submit" name="" value="Search">
          </p>
</form>
        <p><b>Active Questionnaire</b></p>
        <form action="<?php  echo $site_url; ?>/oos/search-submissions.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php  echo $active_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>
        <p>
        <hr />
        </p>
        <p><b>Archived Questionnaire</b></p>
        <form action="<?php  echo $site_url; ?>/oos/search-submissions.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <input type="hidden" name="action_type" value="view_archive" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php  echo $archive_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>
</div>
<div style="clear:left;"></div>
<?
// start basic callback 
	
	
	$get_template->topHTML();
	?>
        <h1>Form - Book a Callback</h1>
        <form method="post" action"<?php echo $site_url; ?>/wedding-questionaire.php" name="getcsvdata">
  <input type="hidden" name="data" value="bookacallback" />
  <div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_from"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_from'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_to"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_to'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <input type="submit" name="action" value="Download CSV" />
</form>
        <p>
        <hr />
        </p>
        <form action="<?php echo $site_url; ?>/oos/book-a-callback.php" method="get">
  <p><strong>Search Surname</strong>
            <input type="text" name="surname" value="" />
            <input type="submit" name="" value="Search">
          </p>
</form>
        <p><b>Active Messages</b></p>
        <form action="<?php echo $site_url; ?>/oos/book-a-callback.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php echo $active_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>
        <p>
        <hr />
        </p>
        <p><b>Archived Messages</b></p>
        <form action="<?php echo $site_url; ?>/oos/book-a-callback.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <input type="hidden" name="action_type" value="view_archive" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php echo $archive_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>

<?
	// contact us
	?>
        <h1>Form - Contact US</h1>
        <form method="post" action"<?php echo $site_url; ?>/wedding-questionaire.php" name="getcsvdata">
  <input type="hidden" name="data" value="contactus" />
  <div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_from"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_from'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_to"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_to'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <input type="submit" name="action" value="Download CSV" />
</form>
        <p>
        <hr />
        </p>
        <form action="<?php echo $site_url; ?>/oos/contact-us.php" method="get">
  <p><strong>Search Surname</strong>
            <input type="text" name="surname" value="" />
            <input type="submit" name="" value="Search">
          </p>
</form>
        <p><b>Active Messages</b></p>
        <form action="<?php echo $site_url; ?>/oos/contact-us.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php echo $active_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>
        <p>
        <hr />
        </p>
        <p><b>Archived Messages</b></p>
        <form action="<?php echo $site_url; ?>/oos/contact-us.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <input type="hidden" name="action_type" value="view_archive" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php echo $archive_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>
<?
// personal consult
	
	?>
        <h1>Form - Personal Consultations</h1>
        <form method="post" action"<?php echo $site_url; ?>/wedding-questionaire.php" name="getcsvdata">
  <input type="hidden" name="data" value="personal" />
  <div style="float:left; width:140px; margin:5px;"><b>Date From</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_from"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_from'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <div style="float:left; width:140px; margin:5px;"><b>Date To</b></div>
  <div style="float:left; margin:5px;">
            <input type="text" name="date_to"/>
            <script language="JavaScript">
	new tcal ({
		
		'formname': 'getcsvdata',
		
		'controlname': 'date_to'
	});
	</script>
          </div>
  <div style="clear:left;"></div>
  <input type="submit" name="action" value="Download CSV" />
</form>
        <p>
        <hr />
        </p>
        <form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="get">
  <p><strong>Search Surname</strong>
            <input type="text" name="surname" value="" />
            <input type="submit" name="" value="Search">
          </p>
</form>
        <p><b>Active Messages</b></p>
        <form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php echo $active_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>
        <p>
        <hr />
        </p>
        <p><b>Archived Messages</b></p>
        <form action="<?php echo $site_url; ?>/oos/personal-consultations.php" method="get">
  <input type="hidden" name="action" value="Continue" />
  <input type="hidden" name="action_type" value="view_archive" />
  <select name="id" class="inputbox_town" size="20" style="width:700px;" onclick="this.form.submit();">
            <?php echo $archive_list; ?>
          </select>
  <p style="margin-top:10px;">
            <input type="submit" name="action" value="Continue">
          </p>
</form>

<?

$get_template->bottomHTML();
$sql_command->close();

?>