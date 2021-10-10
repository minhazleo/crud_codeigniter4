<?php
namespace App\Controllers;

use App\Models\BookModel;
use CodeIgniter\HTTP\Request;
use CodeIgniter\Session\Session;

class Book extends BaseController{
    public function index(){
        $session = \Config\Services::session();
        $data['session'] = $session; 

        $model = new BookModel();
        $booksArray = $model->getRecords();

        $data['books'] = $booksArray;

        return view ("books/list", $data);
    }

    public function create(){

        $session = \Config\Services::session();
        helper('form');

        $data = [];

        if($this->request->getMethod() == 'post'){
            $input = $this->validate([
                    'name' => 'required|min_length[5]',
                    'author' => 'required|min_length[5]',
            ]);

            if($input == true){
                // Form validated successfully, so now we can save values to the database
                $model = new BookModel();

                $model->save([
                    'title' => $this->request->getPost('name'),
                    'author' => $this->request->getPost('author'),
                    'isbn_no' => $this->request->getPost('isbn_no')
                ]);

                $session->setFlashdata('success', 'Record Added Successfully');
                return redirect()->to('/books');

            } else{
                    // Form not validated successfully
                    $data['validation'] = $this->validator;                              
            }
        }
        return view('books/create', $data);
    }

    public function edit($id){

        $session = \Config\Services::session();
        helper('form');

        $model = new BookModel();
        $book = $model->getRow($id);

        //  print_r($book);

        if (empty($book)) {
            $session->setFlashdata('error', 'Record not found');
            return redirect()->to('/books');
        }

        $data = [];
        $data['book'] = $book;

        if($this->request->getMethod() == 'post'){
            $input = $this->validate([
                    'name' => 'required|min_length[5]',
                    'author' => 'required|min_length[5]',
            ]);

            if($input == true){
                // Form validated successfully, so now we can save values to the database
                $model = new BookModel();

                $model->update($id, [
                    'title' => $this->request->getPost('name'),
                    'author' => $this->request->getPost('author'),
                    'isbn_no' => $this->request->getPost('isbn_no')
                ]);

                $session->setFlashdata('success', 'Record Updated Successfully');
                return redirect()->to('/books');

            } else{
                    // Form not validated successfully
                    $data['validation'] = $this->validator;                              
            }
        }
        return view('books/edit', $data);

    }

    public function delete($id){
        
        $session = \Config\Services::session();

        $model = new BookModel();
        $book = $model->getRow($id);

        //  print_r($book);

        if (empty($book)) {
            $session->setFlashdata('error', 'Record not found');
            return redirect()->to('/books');
        }

        $model = new BookModel();
        $model->delete($id);
        $session->setFlashdata('success', 'You have successfully deleted the record ');
        return redirect()->to('/books');
    }
}

?>