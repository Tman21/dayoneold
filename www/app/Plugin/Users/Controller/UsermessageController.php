<?php

App::uses('UsersAppController', 'Users.Controller');

/**
 * Roles Controller
 *
 * @category Controller
 * @package  Croogo.Users.Controller
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class UsermessageController extends UsersAppController
{

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Usermessage';

    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Users.Usermessage', 'Users.User');

    public $helpers = array('Users.User');

    public function index($username = null)
    {
        if (!empty($this->request->data)) {

            if ($this->Auth->user('id') != $this->request->data['Usermessage']['send_to']) {
                $this->Usermessage->create();
                $this->request->data['Usermessage']['sent_from'] = $this->Auth->user('id');
                $this->request->data['Usermessage']['readmessage'] = 0;

                $this->request->data['Usermessage']['date'] = date('Y-m-d H:i:s');

                if ($this->Usermessage->save($this->request->data)) {
                    $lastId = $this->Usermessage->getLastInsertId();
                    if ($this->request->data['Usermessage']['parent_id'] == 0) {
                        $this->Usermessage->query(
                            " UPDATE `usermessages` SET parent_id = '" . $lastId . "' WHERE id = '" . $lastId . "'"
                        );
                    }

                    $this->Session->setFlash(
                        __d('croogo', 'Your Message has been sent successfully.'),
                        'default',
                        array('class' => 'success')
                    );
                    $this->redirect(array('controllers' => 'usermessage', 'action' => 'index/' . $username));
                } else {
                    Croogo::dispatchEvent('Controller.Usersmessage.mesagefailer', $this);
                    $this->Session->setFlash(
                        __d('croogo', 'This Message cannot be sent. Please, try again.'),
                        'default',
                        array('class' => 'error')
                    );
                }
            } else {
                Croogo::dispatchEvent('Controller.Usersmessage.mesagefailer', $this);
                $this->Session->setFlash(
                    __d('croogo', 'This Message cannot be sent to self. Please, try again.'),
                    'default',
                    array('class' => 'error')
                );
            }
        }

        // track the status of the original person we were trying to talk to
        // so we can determine if we should exit out and just display a welcome message below
        $originalUserName = $username;

        if ($username == null) {
            $username = $this->Auth->user('username');
        }
        $user = $this->User->findByUsername($username);
        $loginUser = $this->Auth->user('id');
        $sent_from = $this->Usermessage->find(
            'all',
            array(
                'fields' => '`Usermessage`.`id` as ids,date as date,send_to,sent_from,body,readmessage,attached_files,parent_id,User.username,`User`.`profilepic`,`User`.`id`',
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' =>
                            array('User.id = sent_from')
                    )
                ),
                'conditions' => array(
                    'OR' => array(
                        'AND' => array(
                            'sent_from' => $this->Auth->user('id'),
                            'send_to' => $user['User']['id']
                        ),
                        array('AND' => array('send_to' => $this->Auth->user('id')), 'sent_from' => $user['User']['id'])
                    )
                ),
                'order' => 'ids asc'
            )
        );

        // if we don't have anything to show, then show our welcome to the messages page
        if(count($sent_from) == 0 && $originalUserName == null) {
            return $this->render('Usermessage/welcome');
        }

        // otherwise, we've got things to display, so let's display them

        /* UPDATE MESSAGE AS READ */
        $this->Usermessage->query(
            " UPDATE `usermessages` SET readmessage = '1' WHERE ((`send_to`= '" . $user['User']['id'] . "' && `sent_from`='" . $this->Auth->user(
                'id'
            ) . "') OR (`sent_from`= '" . $user['User']['id'] . "' && `send_to`='" . $this->Auth->user('id') . "'))"
        );
        /*  MESSAGE QUERY */

        if (isset($sent_from[0]['Usermessage']['parent_id']) && $sent_from[0]['Usermessage']['parent_id'] != "") {
            $parentid = $sent_from[0]['Usermessage']['parent_id'];
        } else {

            $parentid = 0;
        }


        $userData = $this->Usermessage->find(
            'all',
            array(

                'fields' => 'send_to,sent_from, userids,id,`Usermessage`.`parent_id`,date,`User`.`username`,`User`.`profilepic`,`User`.`id`',
                'joins' => array(
                    array(
                        'table' => 'users',
                        'alias' => 'User',
                        'type' => 'INNER',
                        'conditions' => array(
                            'OR' =>
                                array('User.id = ((CASE WHEN Usermessage.send_to = "' . $loginUser . '" THEN sent_from ELSE send_to END))')
                        )
                    )
                ),
                'conditions' => array('OR' => array('sent_from' => $loginUser, 'send_to' => $loginUser)),
                'group' => 'username',
                'order' => 'id desc'
            )
        );

        // handle the case where we haven't talked with this person before
        if(count($userData) == 0) {
            $userData = array($user);
        }

        $this->set(compact('user', 'userData', 'sent_from', 'loginUser', 'parentid'));
    }

}
