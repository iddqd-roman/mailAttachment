function mailAttachment($file, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {
        $filename = basename($file);
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));
        $uid = md5(uniqid(time()));
        $header = "From: ".$from_name." <".$from_mail.">\r\n";
        $header .= "Reply-To: ".$replyto."\r\n";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
        $multipart = "--".$uid."\r\n";
        $multipart .= "Content-type:text/plain; charset=utf-8\r\n";
        $multipart .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $multipart .= $message."\r\n\r\n";
        $multipart .= "--".$uid."\r\n";
        $multipart .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
        $multipart .= "Content-Transfer-Encoding: base64\r\n";
        $multipart .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
        $multipart .= $content."\r\n\r\n";
        $multipart .= "--".$uid."--";
        return mail($mailto, $subject, $multipart, $header);
    }
