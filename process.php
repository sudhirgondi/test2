<?php

require_once("include/db_connection.php");



$data = array('note_description' => NULL, 'html' => NULL, 'status' => NULL, 'error' => NULL);

if (isset($_GET['note_id'])) {
	$note_id = $_GET['note_id'];
	$query = "DELETE FROM posts WHERE id = '{$note_id}'";
	if (mysql_query($query)) 
	{
		$data['status']= true;	
	}
}

if (isset($_POST['note_description']) and isset($_POST['note_id'])) {
	$note_description = mysql_real_escape_string($_POST['note_description']);
	$note_id = $_POST['note_id'];

	$query = "UPDATE posts SET description = '{$note_description}' WHERE id = '{$note_id}'";
	if (mysql_query($query)) {
		$data['note_description'] = $_POST['note_description'];
		$data['status']= true;	
	}
}

if(isset($_POST['title']))
{
	//inset new note into db
	$title = mysql_real_escape_string($_POST['title']);
	$query = "INSERT INTO posts (description, title, created_at) VALUES (NULL,'{$title}', NOW())";
	$insert_note = mysql_query($query);

	if ($insert_note) 
	{	
		$html = NULL;
		$just_inserted_note_id = mysql_insert_id(); 
		$query = "SELECT * FROM posts WHERE id = ".$just_inserted_note_id;
		
		$note = fetch_record($query);

		$html = '<div class="display_note rm_note'.$note['id'].'">';
		$html .= '<p>'.$note['title'].'</p>';
		$html .= '<a id="delete_note" name="'.$note['id'].'" href="">delete</a>';
		$html .= '<div class="clear"></div>';
		$html .= '<form class="note" action="process.php" method="post">';
		$html .= '<textarea class="note_area" rows="15" cols="25" name="note_description" placeholder="Enter note description here">'.$note['description'].'</textarea>';
		$html .= '<input type="hidden" name="note_id" value="'.$note['id'].'">';
		$html .= '<input id="post_it" type="submit" hidden="true" value="Add or Update Note" />';
		$html .= '</form> </div>';

		$data['html'] = $html;

    	$data['status']= true;	
	} else 
	{
		$data['error'] = "Unknown error occured. Not able to insert note into db";
		$data['status'] = false;
	}
	
} 
echo json_encode($data);
?>
