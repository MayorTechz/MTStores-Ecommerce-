<?php

/*this handles any file upload. You can edit*/

class Uploader
{
    private $extension;

    private $msg;

    private $data;

    public function __construct()
    {
        $this->extension = ""; //file extension (jpg,mp3,wav etc)

        $this->msg = 0; //return msg

        $this->data = []; //array to get each filename of what is uploaded
    }

    function upload($max_size, $allowed, $upload_folder)
    {
        $this->max_size = $max_size;

        $this->allowed = $allowed;

        $this->upload_folder = $upload_folder;

        foreach ($_FILES as $file) {
            //empty file check

            if (empty($file["tmp_name"])) {
                exit("All pictures must be selected!!");
            }

            //filesize check
            if (filesize($file["tmp_name"]) > $this->max_size) {
                exit("All pictures size must not be greater then 5MB!!");
            }

            //file type check

            $open = finfo_open(FILEINFO_MIME_TYPE);

            $type = finfo_file($open, $file["tmp_name"]);

            $this->extension = explode("/", $type);

            $this->extension = $this->extension[1];

            finfo_close($open);

            if (!in_array($type, $this->allowed)) {
                exit("Allowed file type is .jpg,.png,.jpeg only!!!");
            }

            //try upload

            $this->filename =
                "p_" . bin2hex(random_bytes(4)) . "." . $this->extension;

            $temp = $file["tmp_name"];

            $finalpath = $upload_folder . "/" . $this->filename;

            // Check if file already exists
            if (file_exists($finalpath)) {
                echo "Something Went wrong!! Retry!!";
                exit();
            }

            if (move_uploaded_file($temp, $finalpath)) {
                $this->data[] = $this->filename;

                $this->msg = 1;
            } else {
                $this->msg = 0;

                return $this->msg;
            }
        } //for each

        if (!empty($this->data)) {
            $output = [$this->msg, $this->data];

            return $output;
        }
    } ///upload function
} //class  uploade
