<?php
/**
 * Loggix_Module_Contact - UPM Contact Module for Loggix
 *
 * Readme File
 *
 * @package      Contact
 * @copyright    Copyright (C) UP!
 * @creator-code UPM
 * @author       hijiri
 * @link         http://tkns.homelinux.net/
 * @license      http://www.opensource.org/licenses/bsd-license.php  New BSD License
 * @since        2010.05.10
 * @version      11.1.13
 */

●コンタクトモジュール

■概略
このソフトウェアは、Loggixにメールフォームを設置するモジュールです。

■詳細
普通のメールフォームです。指定したメールアドレスに投稿されたメールを送信します。

項目の追加/削除は出来ませんがテーマファイルを編集すれば外観は自由に変更出来ます。

■インストール/アンインストール方法
インストール
    1.contact/index.phpをテキストエディタで開き、$sendto = 'example@example.com';をメールを受け取るアドレスに編集します。
    2.必要であればへtheme/以下のテンプレートファイルを編集します。
    3./modules/へcontact/をアップロードします。
アンインストール
    /modules/からcontact/を削除します。

■使用方法
インストールすれば何もしなくても直ぐに使用出来ます。 

■その他
UTF-8メールを送信する為、受信するクライアントソフトによってはUTF-8に対応していない場合があります。

■サポート
作者多忙の為サポート出来ません。意見/感想はContactからご連絡ください。

■更新履歴
2011-01-13:タイプミス修正
2010-05-13:公開
