<?php

class Validate
{

    public function isJson($data)
    {
        if (!empty($data)) {
            @json_decode($data);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

    public function vfJson($data, $rule)
    {

        $ct = true;
        $ci = true;
        $cr = true;
        $cta = true;
        $ct_msg = '';
        $ci_msg = '';
        $cr_msg = '';
        $pattern_msg = '';

        if (!$this->isJson($data)) {
            return '{"code":"error","error_msg":"Предоставленный набор данный не является JSON", "error_type":"' . $ct_msg . '", "error_items":"' . $ci_msg . '", "error_rule":"' . $cr_msg . '"}';
        } elseif (!$this->isJson($data)) {
            return '{"code":"error","error_msg":"Предоставленная схема не является JSON", "error_type":"' . $ct_msg . '", "error_items":"' . $ci_msg . '", "error_rule":"' . $cr_msg . '"}';
        } else {
            if (!is_array($data)){
                $data = json_decode($data);
            }
            $rule = json_decode($rule);

            foreach ($rule as $k => $v) {
                switch ($k) {
                    case 'type':
                        switch ($v) {
                            case 'array':
                                if (!is_array($data)) {
                                    $ct = false;
                                    $ct_msg = 'Формат данных первого уровня не соответсвует типу данных - [ ' . $v . ' ]';

                                }
                                $cta = true;
                                break;
                            case 'object':
                                if (!is_object($data)) {
                                    $ct = false;
                                    $ct_msg = 'Формат данных первого уровня не соответсвует типу данных - [ ' . $v . ' ]';
                                }
                                $cta = false;
                                break;
                        }
                        break;
                    case 'items':
                        if ($cta) {
                            $data_r = array();
                        } else {
                            $data_r = new stdClass();
                        }

                        foreach ($v as $vv) {
                            if ($cta) {
                                if (!isset($data[$vv])) {
                                    $ci = false;
                                    $ci_msg .= 'В предоставленном наборе данных отсутсвует ключ - [ ' . $vv . ' ] ';
                                } else {
                                    $data_r[$vv] = $data[$vv];
                                }
                            } else {
                                if (!isset($data->$vv)) {
                                    $ci = false;
                                    $ci_msg .= 'В предоставленном наборе данных отсутсвует ключ - [ ' . $vv . ' ] ';
                                } else {
                                    $data_r->$vv = $data->$vv;
                                }
                            }
                        }
                        $data = $data_r;
                        break;
                    case 'rule':
                        foreach ($v as $kk => $vv) {
                            foreach ($vv as $kkk => $vvv) {
                                switch ($kkk) {
                                    case 'type':
                                        switch ($vvv) {
                                            case 'string':
                                                if ($cta) {

                                                    if (!is_string($data[$kk])) {
                                                        $cr = false;
                                                        $cr_msg .= 'Значение [' . $data[$kk] . '] не является строкой. ';
                                                    }
                                                } else {
                                                    if (!is_string($data->$kk)) {
                                                        $cr = false;
                                                        $cr_msg .= 'Значение [' . $data->$kk . '] не является строкой. ';
                                                    }
                                                }
                                                break;
                                            case 'int':
                                                if ($cta) {
                                                    if (!preg_match_all('/^[0-9.,]+$/i', $data[$kk])) {
                                                        $cr = false;
                                                        $cr_msg .= 'Значение [' . $data[$kk] . '] не является числом. ';
                                                    } else {
                                                        $data[$kk] = $data[$kk] + 0;
                                                    }
                                                } else {
                                                    if (!preg_match_all('/^[0-9.,]+$/i', $data->$kk)) {
                                                        $cr = false;
                                                        $cr_msg .= 'Значение [' . $data->$kk . '] не является числом. ';
                                                    } else {
                                                        $data->$kk = $data->$kk + 0;
                                                    }
                                                }
                                                break;
                                            case 'phone':
                                                if ($cta) {
                                                    $pr = preg_replace('/[^0-9]+/', '', $data[$kk]);
                                                    if (strlen($pr) == 11) {
                                                        $data[$kk] = $pr + 0;
                                                        $pattern_msg .= 'Преобразование телефона осуществлено - [' . $data[$kk] . '] ';
                                                    } else {
                                                        $cr = false;
                                                        $cr_msg .= '[' . $data[$kk] . '] не верный формат телефона. ';
                                                    }
                                                } else {
                                                    $pr = preg_replace('/[^0-9]+/', '', $data->$kk);
                                                    if (strlen($pr) == 11) {
                                                        $data->$kk = $pr;
                                                        $pattern_msg .= 'Преобразование телефона осуществлено - [' . $data->$kk . '] ';
                                                        $data->$kk = $data->$kk + 0;
                                                    } else {
                                                        $cr = false;
                                                        $cr_msg .= '[' . $data->$kk . '] не верный формат телефона. ';
                                                    }
                                                }
                                                break;
                                        }
                                        break;
                                    case 'max_length':
                                        if ($cta) {
                                            if (strlen($data[$kk]) > $vvv) {
                                                $cr = false;
                                                $cr_msg .= 'Длина значения ключа - [' . $kk . '] превышает максимальную - [' . $vvv . '] ';
                                            }
                                        } else {
                                            if (strlen($data->$kk) > $vvv) {
                                                $cr = false;
                                                $cr_msg .= 'Длина значения ключа - [' . $kk . '] превышает максимальную - [' . $vvv . '] ';
                                            }
                                        }

                                        break;
                                    case 'pattern':
                                        if ($cta) {
                                            $data[$kk] = preg_replace($vvv, '', $data[$kk]);
                                            $pattern_msg = 'Преобразование значения ключа [ ' . $kk . ' ] осуществлено - [' . $data[$kk] . ']';
                                        } else {
                                            $data->$kk = preg_replace($vvv, '', $data->$kk);
                                            $pattern_msg = 'Преобразование значения ключа [ ' . $kk . ' ] осуществлено - [' . $data->$kk . ']';
                                        }

                                        break;
                                    case 'match':
                                        if ($cta) {
                                            if (preg_match($vv, $data[$kk])) {
                                                $cr = false;
                                                $cr_msg .= 'Формат строки [' . $kk . '] не совпадает с заданным форматом - [' . $vv . '] ';
                                            }
                                        } else {
                                            if (preg_match($vv, $data->$kk)) {
                                                $cr = false;
                                                $cr_msg .= 'Формат строки [' . $kk . '] не совпадает с заданным форматом - [' . $vv . '] ';
                                            }
                                        }

                                        break;
                                }
                            }
                        }
                        break;
                }
            }
        }

        if (!$ct || !$ci || !$cr) {
            return '{"code":"error", "error_msg":"Предоставленная схема не является JSON", "error_type":"' . $ct_msg . '", "error_items":"' . $ci_msg . '", "error_rule":"' . $cr_msg . '"}';
        } else {
            return '{"code":"ok", "pattern_msg":"' . $pattern_msg . '", "data":' . json_encode($data) . '}';
        }
    }
}