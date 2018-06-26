<?php

namespace webheads\core;

class File 
{
    public static function load($url = '', $to = '') {
        $answer = ['code' => 0, 'file' => '', 'error' => ''];
        if (!empty($url) && !empty($to) && is_dir($to)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            $data = curl_exec($ch);
            if (curl_errno($ch)) {
                $answer['error'] = curl_error($ch);
                $answer['code'] = 500;
            } else {

                $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($code == 200) {
                    $fname = basename($url);
                    $parts = explode('.', $fname);
                    
                    if (isset($parts[count($parts) - 1])) {
                        $file_ext = $parts[count($parts) - 1];
                        unset($parts[count($parts) - 1]);
                        $file_name = implode('.', $parts);
                        $fname = Translit::t($file_name);
                        $fname .= !empty($file_ext)?'.'.$file_ext:'';
                    } else {
                        $fname = Translit::t($fname);
                    }
                    
                    if (!file_exists($to.'/'.$fname)) { 
                        file_put_contents($to.'/'.$fname, $data);
                    }
                    $answer['file'] = $fname;
                }
                $answer['code'] = $code;
            }
            curl_close($ch);
        }
        return $answer;
    }

    public static function getInFolder($folder = '', $options = ['images' => false, 'tree' => false], &$answer = []) {
        $hidden_files = ['.', '..', '.DS_Store'];
        if (!empty($folder) && is_dir($folder)) {
            $folder = rtrim($folder, '/');
            $folder .= '/';
            if ($dh = opendir($folder)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..') {
                        if (in_array($file, $hidden_files)) {
                            continue;
                        }
                        if (!is_dir($folder.$file)) {
                            if ($options['images']) {
                                $file_data = getimagesize($folder.$file);
                                if (is_array($file_data) && strpos($file_data['mime'], 'image') !== false) {
                                    $answer[] = $folder.$file;
                                }
                            } else {
                                $answer[] = $folder.$file;
                            }
                        } else {
                            if ($options['tree']) {
                                $answer[$file] = self::getInFolder($folder.$file, $options);  
                            } else {
                                self::getInFolder($folder.$file, $options, $answer);
                            }
                        }  
                    }
                }
            }
            closedir($dh);         
        }
        return $answer;
    }
}