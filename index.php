<?php

require_once("include/db_connection.php");

?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Coding Dojo - Github testing</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!-- <script type="text/javascript" src="jquery-1.10.2.min.js"></script><br> -->
	<script type="text/javascript">


	    $(document).ready(function(){

            $(document).on('click', 'a', function(){
                var x = $(this)[0].name;
                alert(x);
                // alert('a'.sibling('.note'));
                $.get('process.php', {note_id: x}, function(data){
                    // alert(form.note_id);
                    if(data.status)
                    {
                        alert("deleted note");
                        $('.rm_note' + x).remove();
                    }
                    else
                    {
                        alert(data.error);
                    } 
                }, "json");
                return false;
            });

            $(document).on('submit', '.note', function(){
                alert("subitting");
                var form = $(this);
                $.post(form.attr('action'), form.serialize(), function(data){
                    if(data.status)
                    {
                        
                    }
                    else
                    {
                        alert(data.error);
                    }
                }, "json");
                return false;
            });

            $(document).on('focusout', '.note', function(){
                 var form = $(this);
                 form.submit();
            });

            $("#title_form").on('submit', function(){
                var form = $('#title_form');
                $.post(form.attr('action'), form.serialize(), function(data){
                    if(data.status)
                    {
                        $('#my_notes').prepend(data.html);
                        $('#title').val(""); //clear add a note field

                    }
                    else
                    {
                        alert(data.error);
                    }
                }, "json");
                return false;
            });

        });

</script>

</head>
<body>
<div id="wrapper">
    <p>Notes</p>
<!-- Adding a comment for Github testing -->
	<div id="my_notes">
<?php 

    //fetch all notes and send it back to index.php
    $query = "SELECT * FROM posts ORDER BY created_at DESC";
    $notes = fetch_all($query);

    $counter = 0;
    $html = NULL;
    foreach ($notes as $note) {
?>        
        <div class="display_note rm_note<?php echo $note['id']; ?>">
            <p><?php echo $note['title']?></p>
            <a class="delete_note" name="<?php echo $note['id']; ?>" href="">delete</a>
            <div class="clear"></div>
            <form class="note" action="process.php" method="post">
                <textarea class="note_area" rows="15" cols="25" name="note_description" placeholder="Enter note description here"><?php echo $note['description']?></textarea>
                <input type="hidden" name="note_id" value= "<?php echo $note['id']; ?>">
                <input id="post_it" type="submit" hidden="true" value="Add or Update Note" />
            </form>
        </div>
 <?php 
        $counter++;
    }
?>		
        <div class="clear"></div>
	</div>
    
    <div class="clear"></div>
    
    <form id="title_form" action="process.php" method="post">
        <input id="title" type="text" name="title" placeholder="Enter note title here"> </br></br>
		<input id="post_it" type="submit" value="Add Note" />
    </form>
    
</div>
	
</body>
</html>