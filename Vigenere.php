<?php

class Vigenere
{
    public const ALPHABET = 'abcdefghijklmnopqrstuvwxyz';
    private string $key;

    public function __construct(string $key = null)
    {
        if ($key === null) {
            $this->key = $this->generate_random_key();
        } else {
            $this->key = $key;
        }
    }

    private function generate_random_key(): string
    {
        $random_key = "";

        // generate a key of 100 characters
        for ($i = 0; $i < 100; $i++) {
            $random_key .= self::ALPHABET[random_int(0, 25)];
        }

        return $random_key;
    }

    public function encode(string $plain_text): string {
        $message_key = $this->get_message_key(strlen($plain_text));

        return $this->rot_string($plain_text, $message_key, 1);
    }

    public function decode(string $cipher_text): string
    {
        $message_key = $this->get_message_key(strlen($cipher_text));

        return $this->rot_string($cipher_text, $message_key, -1);
    }

    private function rot_char(string $char, int $shift, int $op): string
    {
        // ascii values covered: 32 to 126 (94 characters)
        $char_pos = ord($char) - 32;

        if ($op) {
            // Ci = Mi + Ki (mod n)
            $new_char = ($char_pos + $shift) % 94;
        } else {
            // Mi = Ci - Ki + n (mod n)
            $new_char = ($char_pos + $shift + 94) % 94;
        }

        return chr($new_char + 32);
    }

    private function rot_string(string $text, string $message_key, int $encoding_factor): string
    {
        $rotated = "";

        for ($i = 0; $i < strlen($text); $i++) {
            // get character ascii value
            $char_ascii = ord($message_key[$i]);
            $shift = ($char_ascii - 32) * $encoding_factor;
            $op = $encoding_factor === 1 ? 1 : 0;
            $rotated .= $this->rot_char($text[$i], $shift, $op);
        }

        return $rotated;
    }

    private function get_message_key(int $message_size): string {
        $num_full_keys = intdiv($message_size, strlen($this->key));
        $num_chr_remaining = $message_size % strlen($this->key);
        $message_key = "";

        if ($num_full_keys > 0) {
            $message_key = str_repeat($this->key, $num_full_keys);
        }

        if ($num_chr_remaining > 0) {
            for ($i = 0; $i < $num_chr_remaining; $i++) {
                $message_key .= $this->key[$i];
            }
        }

        return $message_key;
    }
}

$key = "segurancadedados";
$vigenere = new Vigenere($key);

$cipher_text = $vigenere->encode("meunomeehguilherme");
echo "cipher text: " . $cipher_text . PHP_EOL;

$plain_text = $vigenere->decode($cipher_text) . PHP_EOL;
echo "plain text: " . $plain_text . PHP_EOL;