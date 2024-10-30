-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 30 Paź 2024, 18:53
-- Wersja serwera: 10.4.22-MariaDB
-- Wersja PHP: 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Baza danych: `job_task`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zadanie`
--

CREATE TABLE `zadanie` (
    `id_form` int(11) NOT NULL,
    `name` varchar(30) NOT NULL,
    `surname` varchar(40) NOT NULL,
    `email` varchar(80) NOT NULL,
    `phone` int(9) UNSIGNED NOT NULL,
    `client_no` varchar(12) NOT NULL,
    `choose` tinyint(1) UNSIGNED NOT NULL,
    `account` varchar(26) DEFAULT NULL,
    `agreement1` tinyint(1) UNSIGNED NOT NULL,
    `agreement2` tinyint(1) UNSIGNED NOT NULL,
    `agreement3` tinyint(1) UNSIGNED DEFAULT NULL,
    `user_info` text DEFAULT NULL,
                           `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `zadanie`
--
ALTER TABLE `zadanie`
    ADD PRIMARY KEY (`id_form`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `zadanie`
--
ALTER TABLE `zadanie`
    MODIFY `id_form` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;
