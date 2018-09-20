<?php

class Swift_Bug34Test extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        Swift_Preferences::getInstance()->setCharset('utf-8');
    }

    public function testEmbeddedFilesWithMultipartDataCreateMultipartRelatedContentAsAnAlternative()
    {
        $message = Swift_Message::newInstance();
        $message->setCharset('utf-8');
        $message->setSubject('test subject');
        $message->addPart('plain part', 'text/plain');

        $image = Swift_Image::newInstance('<image data>', 'image.gif', 'image/gif');
        $cid = $message->embed($image);

        $message->setBody('<img src="'.$cid.'" />', 'text/html');

        $message->setTo(array('user@domain.tld' => 'User'));

        $message->setFrom(array('other@domain.tld' => 'Other'));
        $message->setSender(array('other@domain.tld' => 'Other'));

        $id = $message->getId();
        $date = preg_quote(date('r', $message->getDate()), '~');
        $boundary = $message->getBoundary();
        $cidVal = $image->getId();

        $this->assertRegExp(
        '~^'.
        'Sender: Other <other@domain.tld>'."\r\n".
        'Message-ID: <'.$id.'>'."\r\n".
        'Date: '.$date."\r\n".
        'Subject: test subject'."\r\n".
        'From: Other <other@domain.tld>'."\r\n".
        'To: User <user@domain.tld>'."\r\n".
        'MIME-Version: 1.0'."\r\n".
        'content-Type: multipart/alternative;'."\r\n".
        ' boundary="'.$boundary.'"'."\r\n".
        "\r\n\r\n".
        '--'.$boundary."\r\n".
        'content-Type: text/plain; charset=utf-8'."\r\n".
        'content-Transfer-Encoding: quoted-printable'."\r\n".
        "\r\n".
        'plain part'.
        "\r\n\r\n".
        '--'.$boundary."\r\n".
        'content-Type: multipart/related;'."\r\n".
        ' boundary="(.*?)"'."\r\n".
        "\r\n\r\n".
        '--\\1'."\r\n".
        'content-Type: text/html; charset=utf-8'."\r\n".
        'content-Transfer-Encoding: quoted-printable'."\r\n".
        "\r\n".
        '<img.*?/>'.
        "\r\n\r\n".
        '--\\1'."\r\n".
        'content-Type: image/gif; name=image.gif'."\r\n".
        'content-Transfer-Encoding: base64'."\r\n".
        'content-ID: <'.$cidVal.'>'."\r\n".
        'content-Disposition: inline; filename=image.gif'."\r\n".
        "\r\n".
        preg_quote(base64_encode('<image data>'), '~').
        "\r\n\r\n".
        '--\\1--'."\r\n".
        "\r\n\r\n".
        '--'.$boundary.'--'."\r\n".
        '$~D',
        $message->toString()
        );
    }
}
