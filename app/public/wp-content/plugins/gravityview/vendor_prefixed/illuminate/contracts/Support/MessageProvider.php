<?php
/**
 * @license MIT
 *
 * Modified by gravityview on 21-July-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace GravityKit\GravityView\Foundation\ThirdParty\Illuminate\Contracts\Support;

interface MessageProvider
{
    /**
     * Get the messages for the instance.
     *
     * @return \GravityKit\GravityView\Foundation\ThirdParty\Illuminate\Contracts\Support\MessageBag
     */
    public function getMessageBag();
}
