<?php
namespace WP_Statistics\Components;

use WP_STATISTICS\Schedule;

class Event
{
    /**
     * Get a scheduled event.
     *
     * @param string $event The action hook of the event.
     * @return object|false The event object if found, false otherwise.
     */
    public static function get($event)
    {
        return wp_get_scheduled_event($event);
    }

    /**
     * Schedules a WordPress event hook.
     *
     * @param string    $hook       The action hook of the event.
     * @param int       $timestamp  The timestamp for when the event should occur.
     * @param string    $recurrence How often the event should be repeated.
     * @param mixed     $callback   The callback of the event.
     *
     * @return void
     */
    public static function schedule($event, $timestamp, $recurrence, $callback = null)
    {
        wp_schedule_event($timestamp, $recurrence, $event);

        if ($callback) {
            add_action($event, $callback);
        }
    }


    /**
     * Unschedules a WordPress event hook, if it is scheduled.
     *
     * @param string $event The action hook of the event.
     *
     * @return void
     */
    public static function unschedule($event)
    {
        if (self::isScheduled($event)) {
            wp_unschedule_event(wp_next_scheduled($event), $event);
        }
    }

    /**
     * Checks if a WordPress event hook is scheduled.
     *
     * @param string $event The action hook of the event.
     * @return bool True if the event is scheduled, false otherwise.
     */
    public static function isScheduled($event)
    {
        return wp_next_scheduled($event) ? true : false;
    }

    /**
     * Reschedule an already scheduled event hook.
     *
     * @param string $event
     * @param string $newTime
     * @param string $prevTime
     *
     * @return void
     */
    public static function reschedule($event, $newTime, $prevTime = false)
    {
        if ($prevTime && $newTime === $prevTime) return;

        // unschedule previous event
        self::unschedule($event);

        $schedules = Schedule::getSchedules();

        if (isset($schedules[$newTime])) {
            $nextRun = $schedules[$newTime]['next_schedule'];
            self::schedule($event, $nextRun, $newTime);
        }
    }
}