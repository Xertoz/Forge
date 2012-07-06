<?php
    /**
    * ajax.Text.php
    * Copyright 2010-2012 Mattias Lindholm
    *
    * This work is licensed under the Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Unported License.
    * To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-nd/3.0/ or send a letter
    * to Creative Commons, 444 Castro Street, Suite 900, Mountain View, California, 94041, USA.
    */

    namespace forge\modules\Text;

    /**
    * Give AJAX support for Text module
    */
    class Ajax {
        static public function update(\XMLWriter $xml) {
            \forge\components\Accounts::restrict('SiteMap','admin','pages','w');

            try {
                $text = new \forge\modules\Text\db\tables\TextEntry();
                $text->page_id = $_POST['id'];
                $text->select('page_id');
            }
            catch (\Exception $e) {
                throw new \forge\HttpException('Couldn\'t find the text document',\forge\HttpException::HTTP_NOT_FOUND);
            }

            $text->text_content = $_POST['content'];
            $text->save();

            $xml->writeElement('text');
            $xml->writeAttribute('command','update');
            $xml->writeAttribute('id',$_POST['id']);
        }
    }