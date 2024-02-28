<?php

include "Vigenere.php";

function read_file(string $filename): string
{
    return file_get_contents($filename);
}

function get_most_frequent_char(string $cipher_text): string
{
    // Count the occurrences of each character in the ciphertext
    $char_count = array_count_values(str_split($cipher_text));

    // Find the most frequent character in the ciphertext
    arsort($char_count);
    return key($char_count);
}

function find_vigenere_key(string $cipher_text, int $key_length, string $most_freq_char = ' '): string
{
    $alphabet_size = 95;

    $most_freq_cipher_char = get_most_frequent_char($cipher_text);
    echo "most freq char: " . $most_freq_cipher_char . PHP_EOL;
    $shifts = [];

    for ($i = 0; $i < $key_length; $i++) {
        $shift = ord($most_freq_cipher_char) - ord($most_freq_char);
        $shift = ($shift + $alphabet_size) % $alphabet_size;
        $shifts[] = $shift;
        $most_freq_cipher_char = chr(((ord($most_freq_cipher_char) - 32 + 1) % $alphabet_size) + 32);
    }

    $key = '';
    foreach ($shifts as $shift) {
        $key .= chr(($shift % $alphabet_size) + 32);
    }

    return $key;
}

$plain_text = read_file("texto0.txt");
$original_key = "k%)A";
$key_length = 4;

$vigenere = new Vigenere($original_key);
$cipher_text = $vigenere->encode($plain_text); // using the code from the last exercise

$found_key = find_vigenere_key($cipher_text, $key_length);
echo $found_key . PHP_EOL;

$vigenere2 = new Vigenere($found_key);
echo $vigenere2->decode($cipher_text);