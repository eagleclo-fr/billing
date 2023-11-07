<?php
namespace src\Billing\Database;

use src\Database\Database;

class InvoicesTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function createInvoiceTable(int $userid, string $price, string $product, string $date_paid)
    {
        $this->insert = $this->pdo->prepare('INSERT INTO `invoices` SET userid = :userid, price = :price, product = :product, date_created = :date_created, date_paid = :date_paid');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->insert->bindValue(':price', $price, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':product', $product, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_paid', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }

    public function getAllInvoices(int $userid)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `invoices` WHERE userid = :userid ORDER BY id DESC');
        $this->select->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->id = $this->result['id'];
            $this->product = $this->result['product'];
            $this->price = $this->result['price'];
            $this->date_created = date('d/m/Y H:i', strtotime($this->result['date_created']));
            $this->date_paid = date('d/m/Y H:i', strtotime($this->result['date_paid']));

            echo '<tr>
                      <th scope="row"><span class="badge bg-secondary">'.$this->id.'</span></th>
                      <td>'.$this->product.'</td>
                      <td>'.number_format($this->price, 2, ',', ' ').'<i class="bi bi-currency-euro"></i></td>
                      <td>'.$this->date_created.'</td>
                      <td>'.$this->date_paid.'</td>
                      <td><a href="/billing/invoices/'.$this->id.'" class="btn btn-primary">Voir</a></td>
                    </tr>';
        }
    }

    public function getAllInvoicesLimit(int $userid, int $limit)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `invoices` WHERE userid = :userid ORDER BY id DESC LIMIT '.$limit);
        $this->select->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->id = $this->result['id'];
            $this->product = $this->result['product'];
            $this->price = $this->result['price'];
            $this->date_created = date('d/m/Y H:i', strtotime($this->result['date_created']));
            $this->date_paid = date('d/m/Y H:i', strtotime($this->result['date_paid']));

            echo '<tr>
                      <th scope="row"><span class="badge bg-secondary">'.$this->id.'</span></th>
                      <td>'.$this->product.'</td>
                      <td>'.number_format($this->price, 2, ',', ' ').'<i class="bi bi-currency-euro"></i></td>
                      <td>'.$this->date_created.'</td>
                      <td>'.$this->date_paid.'</td>
                      <td><a href="/billing/invoices/'.$this->id.'" class="btn btn-primary">Voir</a></td>
                    </tr>';
        }
    }

    public function getInvoiceTable(int $id_invoice){
        $this->get = $this->pdo->prepare('SELECT * FROM `invoices` WHERE id = :id');
        $this->get->bindValue(':id', $id_invoice, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {

            $this->id = $this->result['id'];
            $this->userid = $this->result['userid'];
            $this->price = $this->result['price'];
            $this->product = $this->result['product'];
            $this->date_created = $this->result['date_created'];
            $this->date_paid = $this->result['date_paid'];
        }
    }

}
