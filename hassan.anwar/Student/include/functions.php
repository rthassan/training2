<?php
/**
 * Created by PhpStorm.
 * User: hassan.anwar
 * Date: 7/13/16
 * Time: 3:17 PM
 */

require_once 'Database.php';

$obj = Database::getInstance();
$db = $obj->getConnection();


function add_student() {

    global $db;
    $student_name = $_REQUEST["name"];
    $dob = $_REQUEST["dob"];
    $father_name = $_REQUEST["fathername"];
    $class = $_REQUEST["class"];
    $address = $_REQUEST["address"];
    $phone_no = $_REQUEST["phoneno"];


    $query = "insert into Student(student_name, dob, father_name, class, address, phone_no, status) values('$student_name', '$dob', '$father_name', '$class', '$address', '$phone_no', 1)";
    $result = mysqli_query($db,$query);

    if($result)
        header("location: index.php");
    else
        echo "Error!";


}

function getAllStudents() {
    global $db;
    $query = "select * from Student";
    $result = mysqli_query($db,$query);
    return $result;
}

function delete_student($id) {
    global $db;
    $query = "delete from Student where id=$id";
    $result = mysqli_query($db,$query);

    if($result)
        header("location: index.php");
    else
        echo "Error!";
}

function get_student($id) {

    global $db;
    $query = "select * from Student where id = $id";
    $result = mysqli_query($db,$query);
    $value = mysqli_fetch_array($result);

    return $value;

}

function update_student($id) {

    global $db;
    $student_name = $_REQUEST["name"];
    $dob = $_REQUEST["dob"];
    $father_name = $_REQUEST["fathername"];
    $class = $_REQUEST["class"];
    $address = $_REQUEST["address"];
    $phone_no = $_REQUEST["phoneno"];

    $query = "update Student set student_name='$student_name', dob='$dob', father_name='$father_name', class='$class', address='$address', phone_no='$phone_no', updated_on=now()  where id = '$id'";
    $result = mysqli_query($db,$query);
    if($result)
        header("location: index.php");
    else
        echo "Error!";

}


function import()
{
    global $db;




        $file = fopen($_FILES["fileToUpload"]["tmp_name"], "r");

        if ($file) {
            while (($data = fgetcsv($file)) !== FALSE) {
                $num = count($data);
                for ($c = 0; $c < $num; $c++) {
                    $col[$c] = $data[$c];
                }

                echo $col[0];
                echo $col[1];
                echo $col[2];
                $query = "insert into Student(student_name, dob, father_name, class, address, phone_no, status) values('$col[0]', '$col[1]', '$col[2]', '$col[3]', '$col[4]', '$col[5]', 1)";
                $result = mysqli_query($db,$query);

                if($result)
                    header("location: index.php");
                else
                    echo "Error!";

            }

            fclose($file);



    }

}


function export() {

    ob_clean();

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=studentRecord.csv');

    $result=getAllStudents();

    $file = fopen('php://output', 'w');

    // output the column headings
    fputcsv($file, array('ID', 'Student Name', 'Date of Birth', 'Father Name', 'Class', 'Address', 'Phone_no', 'Created On', 'Updated on', 'Status'));

    if($file)
    {
        while ($row = mysqli_fetch_assoc($result))
        {

            fputcsv($file, $row);
        }

        fclose($file);

    }
    exit(0);

}
