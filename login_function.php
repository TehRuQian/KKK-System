<?php

function dnd($data){
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
    die;
}

    function send_email($to, $subject, $msg){
        $headers = "MIME-Version 1.0" . "\r\n";
        $headers .= "Content-Type:text/html;charset=UTF-8". "\r\n";
        $headers .= "From: <noreply@kada.com>" . "\r\n";
        $result = mail($to, $subject, $msg, $headers);
        return $result;

    }

    function findUserByEmail($email){
        $sql = "SELECT*FROM  users WHERE email = ?";
        $binds = [$email];
        $result = query($sql, $binds);
        return mysqli_fetch_assoc($result);
    }

    function sanitize($dirty){
        $clean= htmlentities($dirty, ENT_QUOTES, "UTF-8");
        return trim($clean);
    }
    
    function cleanPost($post){
        $clean = [];
        foreach ($post as $key => $value){
            $clean[$key]= sanitize($value);
        }
        return $clean;
    }
    

?>

<script>
    function pwResetEmail() {
        event.preventDefault(); 
        Swal.fire({
            title: 'Input your email address',
            input: 'email',
            inputLabel: 'Your email address',
            inputPlaceholder: 'Enter your email address',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const email = result.value;

                // Send email via an AJAX call
                fetch('send_reset_email.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email: email }),
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email Sent!',
                                text: `An email has been sent to ${email}.`,
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Failed to send email. Please try again later.',
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong. Please try again later.',
                        });
                    });
            }
        });
    }
</script>
