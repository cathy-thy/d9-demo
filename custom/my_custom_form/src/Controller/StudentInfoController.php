<?php

namespace Drupal\my_custom_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;

class StudentInfoController extends ControllerBase
{
    public function display()
    {

        //Table Header
        $header_table = ['id' => t('ID'),
                         'student_name' => t('Name'),
                         'student_gender' => t('Gender'),
                         'student_email' => t('Email'),
                         'opt' => t('Edit'),
                         'opt1' => t('Delete'),];
        $rows = [];

        //Connect to the database and get records from the table
        $conn = Database::getConnection();
        $query = $conn->select('studentinfo', 'si');
        $query->fields('si', ['id','student_name','student_gender','student_email']);
        $results = $query->execute()->fetchAll();

        foreach ($results as $data) {
            $delete = Url::fromUserInput('/my_custom_form/form/studentinfo/delete/' . $data->id);
            $edit = Url::fromUserInput('/my_custom_form/form/studentinfo/data?id=' . $data->id);

            $rows[] = [
                'id' => $data->id,
                'student_name' => $data->student_name,
                'student_gener' => $data->student_gender,
                'student_email' => $data->student_email,
                'opt' => Link::fromTextAndUrl('Edit', $edit)->toString(),
                'opt1' => Link::fromTextAndUrl('Delete', $delete)->toString(),
            ];
        }

        $add = Url::fromUserInput('/my_custom_form/form/studentinfo/data');
        $text = "Add User";


        $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => t('No records found'),
            '#caption' => Link::fromTextAndUrl($text, $add)->toString(),
        ];
        return $form;
    }
}
