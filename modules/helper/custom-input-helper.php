<?php

namespace Custom\Helper;

defined('ABSPATH') || die("Direct access not allowed");

class CustomInput
{
    public static function inputGroup (array $attributes = [], Array $wrapperAttributes = []) {
        if (!$attributes or empty($attributes)) {
            return;
        } else {
            echo '<div id=="'.$wrapperAttributes && $wrapperAttributes['id'] ? $wrapperAttributes['id'] : ''.'" class="'.$wrapperAttributes && $wrapperAttributes['class'] ? $wrapperAttributes['class'] : ''.'">';
            echo '<label style="form-label" for="'.$attributes['id'].'">'.$attributes['label'].'</label>';
            switch ($attributes['type']) {
                case 'number':
                    echo '<input type="number" id="' . $attributes['id'] . '" name="' . $attributes['name'] . '" min="' . ($attributes['min'] ?? 0) . '" max="' . ($attributes['max'] ?? 100) . '" step="' . ($attributes['step'] ?? '0.01') . '" value="' . ($attributes['value'] ?? '') . '" />';
                    break;
                case 'text-area':
                    echo '<textarea id="ci_' . $attributes['id'] . '" name="' . $attributes['name'] . '" rows="4">' . $attributes['value'] . '</textarea>';
                    break;
                default:
                    echo '<input type="text" id="ci_' . $attributes['id'] . '" name="' . $attributes['name'] . '" placeholder="' . ($attributes['placeholder'] ?? 'Please insert your input...') . '" value="' . $attributes['value'] . '"></input>';
            }
            echo '</div>';
        }
    }


    public static function inputGroupAll (array $attributes = [], Bool $with_nonce = false, $nonce_action = -1, $nonce_name = "_custom_nonce")
    {
        if (!$atributes or count($atributes) <= 0) {
            return;
        } else {
            echo '<div style="display: flex; flex-direction: column; gap: 0.5rem;">';
            if ($with_nonce) {
                wp_nonce_field($nonce_action, $nonce_name . "_nonce");
            }

            foreach ($atributes as $attrib) {
                echo '<div class="'.($attrib['wrapper-class']) ? $attrib['wrapper-class'] : "flex flex-col flex-nowrap items-start gap-2".'">';
                echo '<label style="display; block; font-weight: bold; color: rgba(0, 0, 0, 1);" for="' . $attrib['id'] . '">' . $attrib['label'] . (isset($attrib['required']) && $attrib['required'] ? '&nbsp<small style="color: red;">*</small>' : '') . '</label>';
                switch ($attrib['type']) {
                    case 'number':
                        echo '<input class="'.$attrib['class'] ? $attrib['class'] : 'form-control'.'" type="number" id="' . $attrib['id'] . '" name="' . $attrib['name'] . '" min="' . ($attrib['min'] ?? 0) . '" max="' . ($attrib['max'] ?? 100) . '" step="' . ($attrib['step'] ?? '0.01') . '" placeholder="' . ($attrib['placeholder'] ?? 'Please insert your input...') . '" value="' . ($attrib['value'] ?? '') . '"  ' . (isset($attrib['required']) && $attrib['required'] ? 'required="required"' : '') . '/>';
                        break;
                    case 'textarea':
                        echo '<textarea class="'.$attrib['class'] ? $attrib['class'] : 'form-control'.'" id="' . $attrib['id'] . '" name="' . $attrib['name'] . '" rows="4" placeholder="' . ($attrib['placeholder'] ?? 'Please insert your input...') . '" ' . (isset($attrib['required']) && $attrib['required'] ? 'required="required"' : '') . '>' . $attrib['value'] . '</textarea>';
                        break;
                    case 'select':
                        echo '<select class="'.$attrib['class'] ? $attrib['class'] : 'form-control'.'" type="number" id="' . $attrib['id'] . '" name="' . $attrib['name'] . '" ' . (isset($attrib['required']) && $attrib['required'] ? 'required="required"' : '') . '>';
                        echo '<option value="">' . ($attrib['placeholder'] ?? 'Please choose option') . '</option>';
                        foreach ($attrib['options'] as $key => $value) {
                            echo '<option value="' . $key . '" ' . ($attrib['value'] == $key ? 'selected="selected"' : '') . '>' . $value . '</option>';
                        }
                        echo '</select>';
                        break;
                    default:
                        echo '<input class="'.$attrib['class'] ? $attrib['class'] : 'form-control'.'" type="text" id="' . $attrib['id'] . '" name="' . $attrib['name'] . '" placeholder="' . ($attrib['placeholder'] ?? 'Please insert your input...') . '" value="' . $attrib['value'] . '" ' . (isset($attrib['required']) && $attrib['required'] ? 'required="required"' : '') . ' />';
                }
                echo '</div>';
            }
            echo '</div>';
        }
    }
}
