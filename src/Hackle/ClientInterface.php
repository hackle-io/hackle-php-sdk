<?php

namespace Hackle;

interface ClientInterface
{

    /**
     * Decide the variation to expose to the user for experiment.
     *
     * This method return the default variation (A) if:
     * - The experiment key is invalid
     * - The experiment has not started yet
     * - The user is not allocated to the experiment
     * - The decided variation has been dropped
     *
     * @param int $experimentKey the unique key of the experiment. MUST NOT be null.
     * @param string $userId the identifier of your customer (e.g. user_email, account_id, etc.) MUST NOT be null.
     * @param string $defaultVariation the default variation of the experiment.
     *
     * @return string the decided variation for the user, or the default variation.
     *                (A, B, C, D, E, F, G, H, I, J)
     */
    public function variation($experimentKey, $userId, $defaultVariation = 'A');

    /**
     * Records the event performed by the user.
     *
     * @param string $eventKey the unique key of the event. MUST NOT be null.
     * @param string $userId the identifier of user that performed the event. MUST NOT be null.
     * @param float $value additional numeric value of the event (e.g. purchase_amount, api_latency, etc.)
     */
    public function track($eventKey, $userId, $value = null);
}