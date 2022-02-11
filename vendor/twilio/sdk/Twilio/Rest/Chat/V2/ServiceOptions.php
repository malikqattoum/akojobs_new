<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Chat\V2;

use Twilio\Options;
use Twilio\Values;

abstract class ServiceOptions {
    /**
     * @param string $friendlyName A string to describe the resource
     * @param string $defaultServiceRoleSid The service role assigned to users when
     *                                      they are added to the service
     * @param string $defaultChannelRoleSid The channel role assigned to users when
     *                                      they are added to a channel
     * @param string $defaultChannelCreatorRoleSid The channel role assigned to a
     *                                             channel creator when they join a
     *                                             new channel
     * @param boolean $readStatusEnabled Whether to enable the Message Consumption
     *                                   Horizon feature
     * @param boolean $reachabilityEnabled Whether to enable the Reachability
     *                                     Indicator feature for this Service
     *                                     instance
     * @param integer $typingIndicatorTimeout How long in seconds to wait before
     *                                        assuming the user is no longer typing
     * @param integer $consumptionReportInterval DEPRECATED
     * @param boolean $notificationsNewMessageEnabled Whether to send a
     *                                                notification when a new
     *                                                message is added to a channel
     * @param string $notificationsNewMessageTemplate The template to use to create
     *                                                the notification text
     *                                                displayed when a new message
     *                                                is added to a channel
     * @param string $notificationsNewMessageSound The name of the sound to play
     *                                             when a new message is added to a
     *                                             channel
     * @param boolean $notificationsNewMessageBadgeCountEnabled Whether the new
     *                                                          message badge is
     *                                                          enabled
     * @param boolean $notificationsAddedToChannelEnabled Whether to send a
     *                                                    notification when a
     *                                                    member is added to a
     *                                                    channel
     * @param string $notificationsAddedToChannelTemplate The template to use to
     *                                                    create the notification
     *                                                    text displayed when a
     *                                                    member is added to a
     *                                                    channel
     * @param string $notificationsAddedToChannelSound The name of the sound to
     *                                                 play when a member is added
     *                                                 to a channel
     * @param boolean $notificationsRemovedFromChannelEnabled Whether to send a
     *                                                        notification to a
     *                                                        user when they are
     *                                                        removed from a channel
     * @param string $notificationsRemovedFromChannelTemplate The template to use
     *                                                        to create the
     *                                                        notification text
     *                                                        displayed to a user
     *                                                        when they are removed
     * @param string $notificationsRemovedFromChannelSound The name of the sound to
     *                                                     play to a user when they
     *                                                     are removed from a
     *                                                     channel
     * @param boolean $notificationsInvitedToChannelEnabled Whether to send a
     *                                                      notification when a
     *                                                      user is invited to a
     *                                                      channel
     * @param string $notificationsInvitedToChannelTemplate The template to use to
     *                                                      create the notification
     *                                                      text displayed when a
     *                                                      user is invited to a
     *                                                      channel
     * @param string $notificationsInvitedToChannelSound The name of the sound to
     *                                                   play when a user is
     *                                                   invited to a channel
     * @param string $preWebhookUrl The webhook URL for pre-event webhooks
     * @param string $postWebhookUrl The URL for post-event webhooks
     * @param string $webhookMethod The HTTP method  to use for both PRE and POST
     *                              webhooks
     * @param string $webhookFilters The list of WebHook events that are enabled
     *                               for this Service instance
     * @param integer $limitsChannelMembers The maximum number of Members that can
     *                                      be added to Channels within this Service
     * @param integer $limitsUserChannels The maximum number of Channels Users can
     *                                    be a Member of within this Service
     * @param string $mediaCompatibilityMessage The message to send when a media
     *                                          message has no text
     * @param integer $preWebhookRetryCount Count of times webhook will be retried
     *                                      in case of timeout or 429/503/504 HTTP
     *                                      responses
     * @param integer $postWebhookRetryCount The number of times calls to the
     *                                       `post_webhook_url` will be retried
     * @param boolean $notificationsLogEnabled Whether to log notifications
     * @return UpdateServiceOptions Options builder
     */
    public static function update($friendlyName = Values::NONE, $defaultServiceRoleSid = Values::NONE, $defaultChannelRoleSid = Values::NONE, $defaultChannelCreatorRoleSid = Values::NONE, $readStatusEnabled = Values::NONE, $reachabilityEnabled = Values::NONE, $typingIndicatorTimeout = Values::NONE, $consumptionReportInterval = Values::NONE, $notificationsNewMessageEnabled = Values::NONE, $notificationsNewMessageTemplate = Values::NONE, $notificationsNewMessageSound = Values::NONE, $notificationsNewMessageBadgeCountEnabled = Values::NONE, $notificationsAddedToChannelEnabled = Values::NONE, $notificationsAddedToChannelTemplate = Values::NONE, $notificationsAddedToChannelSound = Values::NONE, $notificationsRemovedFromChannelEnabled = Values::NONE, $notificationsRemovedFromChannelTemplate = Values::NONE, $notificationsRemovedFromChannelSound = Values::NONE, $notificationsInvitedToChannelEnabled = Values::NONE, $notificationsInvitedToChannelTemplate = Values::NONE, $notificationsInvitedToChannelSound = Values::NONE, $preWebhookUrl = Values::NONE, $postWebhookUrl = Values::NONE, $webhookMethod = Values::NONE, $webhookFilters = Values::NONE, $limitsChannelMembers = Values::NONE, $limitsUserChannels = Values::NONE, $mediaCompatibilityMessage = Values::NONE, $preWebhookRetryCount = Values::NONE, $postWebhookRetryCount = Values::NONE, $notificationsLogEnabled = Values::NONE) {
        return new UpdateServiceOptions($friendlyName, $defaultServiceRoleSid, $defaultChannelRoleSid, $defaultChannelCreatorRoleSid, $readStatusEnabled, $reachabilityEnabled, $typingIndicatorTimeout, $consumptionReportInterval, $notificationsNewMessageEnabled, $notificationsNewMessageTemplate, $notificationsNewMessageSound, $notificationsNewMessageBadgeCountEnabled, $notificationsAddedToChannelEnabled, $notificationsAddedToChannelTemplate, $notificationsAddedToChannelSound, $notificationsRemovedFromChannelEnabled, $notificationsRemovedFromChannelTemplate, $notificationsRemovedFromChannelSound, $notificationsInvitedToChannelEnabled, $notificationsInvitedToChannelTemplate, $notificationsInvitedToChannelSound, $preWebhookUrl, $postWebhookUrl, $webhookMethod, $webhookFilters, $limitsChannelMembers, $limitsUserChannels, $mediaCompatibilityMessage, $preWebhookRetryCount, $postWebhookRetryCount, $notificationsLogEnabled);
    }
}

class UpdateServiceOptions extends Options {
    /**
     * @param string $friendlyName A string to describe the resource
     * @param string $defaultServiceRoleSid The service role assigned to users when
     *                                      they are added to the service
     * @param string $defaultChannelRoleSid The channel role assigned to users when
     *                                      they are added to a channel
     * @param string $defaultChannelCreatorRoleSid The channel role assigned to a
     *                                             channel creator when they join a
     *                                             new channel
     * @param boolean $readStatusEnabled Whether to enable the Message Consumption
     *                                   Horizon feature
     * @param boolean $reachabilityEnabled Whether to enable the Reachability
     *                                     Indicator feature for this Service
     *                                     instance
     * @param integer $typingIndicatorTimeout How long in seconds to wait before
     *                                        assuming the user is no longer typing
     * @param integer $consumptionReportInterval DEPRECATED
     * @param boolean $notificationsNewMessageEnabled Whether to send a
     *                                                notification when a new
     *                                                message is added to a channel
     * @param string $notificationsNewMessageTemplate The template to use to create
     *                                                the notification text
     *                                                displayed when a new message
     *                                                is added to a channel
     * @param string $notificationsNewMessageSound The name of the sound to play
     *                                             when a new message is added to a
     *                                             channel
     * @param boolean $notificationsNewMessageBadgeCountEnabled Whether the new
     *                                                          message badge is
     *                                                          enabled
     * @param boolean $notificationsAddedToChannelEnabled Whether to send a
     *                                                    notification when a
     *                                                    member is added to a
     *                                                    channel
     * @param string $notificationsAddedToChannelTemplate The template to use to
     *                                                    create the notification
     *                                                    text displayed when a
     *                                                    member is added to a
     *                                                    channel
     * @param string $notificationsAddedToChannelSound The name of the sound to
     *                                                 play when a member is added
     *                                                 to a channel
     * @param boolean $notificationsRemovedFromChannelEnabled Whether to send a
     *                                                        notification to a
     *                                                        user when they are
     *                                                        removed from a channel
     * @param string $notificationsRemovedFromChannelTemplate The template to use
     *                                                        to create the
     *                                                        notification text
     *                                                        displayed to a user
     *                                                        when they are removed
     * @param string $notificationsRemovedFromChannelSound The name of the sound to
     *                                                     play to a user when they
     *                                                     are removed from a
     *                                                     channel
     * @param boolean $notificationsInvitedToChannelEnabled Whether to send a
     *                                                      notification when a
     *                                                      user is invited to a
     *                                                      channel
     * @param string $notificationsInvitedToChannelTemplate The template to use to
     *                                                      create the notification
     *                                                      text displayed when a
     *                                                      user is invited to a
     *                                                      channel
     * @param string $notificationsInvitedToChannelSound The name of the sound to
     *                                                   play when a user is
     *                                                   invited to a channel
     * @param string $preWebhookUrl The webhook URL for pre-event webhooks
     * @param string $postWebhookUrl The URL for post-event webhooks
     * @param string $webhookMethod The HTTP method  to use for both PRE and POST
     *                              webhooks
     * @param string $webhookFilters The list of WebHook events that are enabled
     *                               for this Service instance
     * @param integer $limitsChannelMembers The maximum number of Members that can
     *                                      be added to Channels within this Service
     * @param integer $limitsUserChannels The maximum number of Channels Users can
     *                                    be a Member of within this Service
     * @param string $mediaCompatibilityMessage The message to send when a media
     *                                          message has no text
     * @param integer $preWebhookRetryCount Count of times webhook will be retried
     *                                      in case of timeout or 429/503/504 HTTP
     *                                      responses
     * @param integer $postWebhookRetryCount The number of times calls to the
     *                                       `post_webhook_url` will be retried
     * @param boolean $notificationsLogEnabled Whether to log notifications
     */
    public function __construct($friendlyName = Values::NONE, $defaultServiceRoleSid = Values::NONE, $defaultChannelRoleSid = Values::NONE, $defaultChannelCreatorRoleSid = Values::NONE, $readStatusEnabled = Values::NONE, $reachabilityEnabled = Values::NONE, $typingIndicatorTimeout = Values::NONE, $consumptionReportInterval = Values::NONE, $notificationsNewMessageEnabled = Values::NONE, $notificationsNewMessageTemplate = Values::NONE, $notificationsNewMessageSound = Values::NONE, $notificationsNewMessageBadgeCountEnabled = Values::NONE, $notificationsAddedToChannelEnabled = Values::NONE, $notificationsAddedToChannelTemplate = Values::NONE, $notificationsAddedToChannelSound = Values::NONE, $notificationsRemovedFromChannelEnabled = Values::NONE, $notificationsRemovedFromChannelTemplate = Values::NONE, $notificationsRemovedFromChannelSound = Values::NONE, $notificationsInvitedToChannelEnabled = Values::NONE, $notificationsInvitedToChannelTemplate = Values::NONE, $notificationsInvitedToChannelSound = Values::NONE, $preWebhookUrl = Values::NONE, $postWebhookUrl = Values::NONE, $webhookMethod = Values::NONE, $webhookFilters = Values::NONE, $limitsChannelMembers = Values::NONE, $limitsUserChannels = Values::NONE, $mediaCompatibilityMessage = Values::NONE, $preWebhookRetryCount = Values::NONE, $postWebhookRetryCount = Values::NONE, $notificationsLogEnabled = Values::NONE) {
        $this->options['friendlyName'] = $friendlyName;
        $this->options['defaultServiceRoleSid'] = $defaultServiceRoleSid;
        $this->options['defaultChannelRoleSid'] = $defaultChannelRoleSid;
        $this->options['defaultChannelCreatorRoleSid'] = $defaultChannelCreatorRoleSid;
        $this->options['readStatusEnabled'] = $readStatusEnabled;
        $this->options['reachabilityEnabled'] = $reachabilityEnabled;
        $this->options['typingIndicatorTimeout'] = $typingIndicatorTimeout;
        $this->options['consumptionReportInterval'] = $consumptionReportInterval;
        $this->options['notificationsNewMessageEnabled'] = $notificationsNewMessageEnabled;
        $this->options['notificationsNewMessageTemplate'] = $notificationsNewMessageTemplate;
        $this->options['notificationsNewMessageSound'] = $notificationsNewMessageSound;
        $this->options['notificationsNewMessageBadgeCountEnabled'] = $notificationsNewMessageBadgeCountEnabled;
        $this->options['notificationsAddedToChannelEnabled'] = $notificationsAddedToChannelEnabled;
        $this->options['notificationsAddedToChannelTemplate'] = $notificationsAddedToChannelTemplate;
        $this->options['notificationsAddedToChannelSound'] = $notificationsAddedToChannelSound;
        $this->options['notificationsRemovedFromChannelEnabled'] = $notificationsRemovedFromChannelEnabled;
        $this->options['notificationsRemovedFromChannelTemplate'] = $notificationsRemovedFromChannelTemplate;
        $this->options['notificationsRemovedFromChannelSound'] = $notificationsRemovedFromChannelSound;
        $this->options['notificationsInvitedToChannelEnabled'] = $notificationsInvitedToChannelEnabled;
        $this->options['notificationsInvitedToChannelTemplate'] = $notificationsInvitedToChannelTemplate;
        $this->options['notificationsInvitedToChannelSound'] = $notificationsInvitedToChannelSound;
        $this->options['preWebhookUrl'] = $preWebhookUrl;
        $this->options['postWebhookUrl'] = $postWebhookUrl;
        $this->options['webhookMethod'] = $webhookMethod;
        $this->options['webhookFilters'] = $webhookFilters;
        $this->options['limitsChannelMembers'] = $limitsChannelMembers;
        $this->options['limitsUserChannels'] = $limitsUserChannels;
        $this->options['mediaCompatibilityMessage'] = $mediaCompatibilityMessage;
        $this->options['preWebhookRetryCount'] = $preWebhookRetryCount;
        $this->options['postWebhookRetryCount'] = $postWebhookRetryCount;
        $this->options['notificationsLogEnabled'] = $notificationsLogEnabled;
    }

    /**
     * A descriptive string that you create to describe the resource.
     * 
     * @param string $friendlyName A string to describe the resource
     * @return $this Fluent Builder
     */
    public function setFriendlyName($friendlyName) {
        $this->options['friendlyName'] = $friendlyName;
        return $this;
    }

    /**
     * The service role assigned to users when they are added to the service. See the [Roles endpoint](https://www.twilio.com/docs/chat/api/roles) for more details.
     * 
     * @param string $defaultServiceRoleSid The service role assigned to users when
     *                                      they are added to the service
     * @return $this Fluent Builder
     */
    public function setDefaultServiceRoleSid($defaultServiceRoleSid) {
        $this->options['defaultServiceRoleSid'] = $defaultServiceRoleSid;
        return $this;
    }

    /**
     * The channel role assigned to users when they are added to a channel. See the [Roles endpoint](https://www.twilio.com/docs/chat/api/roles) for more details.
     * 
     * @param string $defaultChannelRoleSid The channel role assigned to users when
     *                                      they are added to a channel
     * @return $this Fluent Builder
     */
    public function setDefaultChannelRoleSid($defaultChannelRoleSid) {
        $this->options['defaultChannelRoleSid'] = $defaultChannelRoleSid;
        return $this;
    }

    /**
     * The channel role assigned to a channel creator when they join a new channel. See the [Roles endpoint](https://www.twilio.com/docs/chat/api/roles) for more details.
     * 
     * @param string $defaultChannelCreatorRoleSid The channel role assigned to a
     *                                             channel creator when they join a
     *                                             new channel
     * @return $this Fluent Builder
     */
    public function setDefaultChannelCreatorRoleSid($defaultChannelCreatorRoleSid) {
        $this->options['defaultChannelCreatorRoleSid'] = $defaultChannelCreatorRoleSid;
        return $this;
    }

    /**
     * Whether to enable the [Message Consumption Horizon](https://www.twilio.com/docs/chat/consumption-horizon) feature. The default is `true`.
     * 
     * @param boolean $readStatusEnabled Whether to enable the Message Consumption
     *                                   Horizon feature
     * @return $this Fluent Builder
     */
    public function setReadStatusEnabled($readStatusEnabled) {
        $this->options['readStatusEnabled'] = $readStatusEnabled;
        return $this;
    }

    /**
     * Whether to enable the [Reachability Indicator](https://www.twilio.com/docs/chat/reachability-indicator) for this Service instance. The default is `false`.
     * 
     * @param boolean $reachabilityEnabled Whether to enable the Reachability
     *                                     Indicator feature for this Service
     *                                     instance
     * @return $this Fluent Builder
     */
    public function setReachabilityEnabled($reachabilityEnabled) {
        $this->options['reachabilityEnabled'] = $reachabilityEnabled;
        return $this;
    }

    /**
     * How long in seconds after a `started typing` event until clients should assume that user is no longer typing, even if no `ended typing` message was received.  The default is 5 seconds.
     * 
     * @param integer $typingIndicatorTimeout How long in seconds to wait before
     *                                        assuming the user is no longer typing
     * @return $this Fluent Builder
     */
    public function setTypingIndicatorTimeout($typingIndicatorTimeout) {
        $this->options['typingIndicatorTimeout'] = $typingIndicatorTimeout;
        return $this;
    }

    /**
     * DEPRECATED. The interval in seconds between consumption reports submission batches from client endpoints.
     * 
     * @param integer $consumptionReportInterval DEPRECATED
     * @return $this Fluent Builder
     */
    public function setConsumptionReportInterval($consumptionReportInterval) {
        $this->options['consumptionReportInterval'] = $consumptionReportInterval;
        return $this;
    }

    /**
     * Whether to send a notification when a new message is added to a channel. Can be: `true` or `false` and the default is `false`.
     * 
     * @param boolean $notificationsNewMessageEnabled Whether to send a
     *                                                notification when a new
     *                                                message is added to a channel
     * @return $this Fluent Builder
     */
    public function setNotificationsNewMessageEnabled($notificationsNewMessageEnabled) {
        $this->options['notificationsNewMessageEnabled'] = $notificationsNewMessageEnabled;
        return $this;
    }

    /**
     * The template to use to create the notification text displayed when a new message is added to a channel and `notifications.new_message.enabled` is `true`.
     * 
     * @param string $notificationsNewMessageTemplate The template to use to create
     *                                                the notification text
     *                                                displayed when a new message
     *                                                is added to a channel
     * @return $this Fluent Builder
     */
    public function setNotificationsNewMessageTemplate($notificationsNewMessageTemplate) {
        $this->options['notificationsNewMessageTemplate'] = $notificationsNewMessageTemplate;
        return $this;
    }

    /**
     * The name of the sound to play when a new message is added to a channel and `notifications.new_message.enabled` is `true`.
     * 
     * @param string $notificationsNewMessageSound The name of the sound to play
     *                                             when a new message is added to a
     *                                             channel
     * @return $this Fluent Builder
     */
    public function setNotificationsNewMessageSound($notificationsNewMessageSound) {
        $this->options['notificationsNewMessageSound'] = $notificationsNewMessageSound;
        return $this;
    }

    /**
     * Whether the new message badge is enabled. Can be: `true` or `false` and the default is `false`.
     * 
     * @param boolean $notificationsNewMessageBadgeCountEnabled Whether the new
     *                                                          message badge is
     *                                                          enabled
     * @return $this Fluent Builder
     */
    public function setNotificationsNewMessageBadgeCountEnabled($notificationsNewMessageBadgeCountEnabled) {
        $this->options['notificationsNewMessageBadgeCountEnabled'] = $notificationsNewMessageBadgeCountEnabled;
        return $this;
    }

    /**
     * Whether to send a notification when a member is added to a channel. Can be: `true` or `false` and the default is `false`.
     * 
     * @param boolean $notificationsAddedToChannelEnabled Whether to send a
     *                                                    notification when a
     *                                                    member is added to a
     *                                                    channel
     * @return $this Fluent Builder
     */
    public function setNotificationsAddedToChannelEnabled($notificationsAddedToChannelEnabled) {
        $this->options['notificationsAddedToChannelEnabled'] = $notificationsAddedToChannelEnabled;
        return $this;
    }

    /**
     * The template to use to create the notification text displayed when a member is added to a channel and `notifications.added_to_channel.enabled` is `true`.
     * 
     * @param string $notificationsAddedToChannelTemplate The template to use to
     *                                                    create the notification
     *                                                    text displayed when a
     *                                                    member is added to a
     *                                                    channel
     * @return $this Fluent Builder
     */
    public function setNotificationsAddedToChannelTemplate($notificationsAddedToChannelTemplate) {
        $this->options['notificationsAddedToChannelTemplate'] = $notificationsAddedToChannelTemplate;
        return $this;
    }

    /**
     * The name of the sound to play when a member is added to a channel and `notifications.added_to_channel.enabled` is `true`.
     * 
     * @param string $notificationsAddedToChannelSound The name of the sound to
     *                                                 play when a member is added
     *                                                 to a channel
     * @return $this Fluent Builder
     */
    public function setNotificationsAddedToChannelSound($notificationsAddedToChannelSound) {
        $this->options['notificationsAddedToChannelSound'] = $notificationsAddedToChannelSound;
        return $this;
    }

    /**
     * Whether to send a notification to a user when they are removed from a channel. Can be: `true` or `false` and the default is `false`.
     * 
     * @param boolean $notificationsRemovedFromChannelEnabled Whether to send a
     *                                                        notification to a
     *                                                        user when they are
     *                                                        removed from a channel
     * @return $this Fluent Builder
     */
    public function setNotificationsRemovedFromChannelEnabled($notificationsRemovedFromChannelEnabled) {
        $this->options['notificationsRemovedFromChannelEnabled'] = $notificationsRemovedFromChannelEnabled;
        return $this;
    }

    /**
     * The template to use to create the notification text displayed to a user when they are removed from a channel and `notifications.removed_from_channel.enabled` is `true`.
     * 
     * @param string $notificationsRemovedFromChannelTemplate The template to use
     *                                                        to create the
     *                                                        notification text
     *                                                        displayed to a user
     *                                                        when they are removed
     * @return $this Fluent Builder
     */
    public function setNotificationsRemovedFromChannelTemplate($notificationsRemovedFromChannelTemplate) {
        $this->options['notificationsRemovedFromChannelTemplate'] = $notificationsRemovedFromChannelTemplate;
        return $this;
    }

    /**
     * The name of the sound to play to a user when they are removed from a channel and `notifications.removed_from_channel.enabled` is `true`.
     * 
     * @param string $notificationsRemovedFromChannelSound The name of the sound to
     *                                                     play to a user when they
     *                                                     are removed from a
     *                                                     channel
     * @return $this Fluent Builder
     */
    public function setNotificationsRemovedFromChannelSound($notificationsRemovedFromChannelSound) {
        $this->options['notificationsRemovedFromChannelSound'] = $notificationsRemovedFromChannelSound;
        return $this;
    }

    /**
     * Whether to send a notification when a user is invited to a channel. Can be: `true` or `false` and the default is `false`.
     * 
     * @param boolean $notificationsInvitedToChannelEnabled Whether to send a
     *                                                      notification when a
     *                                                      user is invited to a
     *                                                      channel
     * @return $this Fluent Builder
     */
    public function setNotificationsInvitedToChannelEnabled($notificationsInvitedToChannelEnabled) {
        $this->options['notificationsInvitedToChannelEnabled'] = $notificationsInvitedToChannelEnabled;
        return $this;
    }

    /**
     * The template to use to create the notification text displayed when a user is invited to a channel and `notifications.invited_to_channel.enabled` is `true`.
     * 
     * @param string $notificationsInvitedToChannelTemplate The template to use to
     *                                                      create the notification
     *                                                      text displayed when a
     *                                                      user is invited to a
     *                                                      channel
     * @return $this Fluent Builder
     */
    public function setNotificationsInvitedToChannelTemplate($notificationsInvitedToChannelTemplate) {
        $this->options['notificationsInvitedToChannelTemplate'] = $notificationsInvitedToChannelTemplate;
        return $this;
    }

    /**
     * The name of the sound to play when a user is invited to a channel and `notifications.invited_to_channel.enabled` is `true`.
     * 
     * @param string $notificationsInvitedToChannelSound The name of the sound to
     *                                                   play when a user is
     *                                                   invited to a channel
     * @return $this Fluent Builder
     */
    public function setNotificationsInvitedToChannelSound($notificationsInvitedToChannelSound) {
        $this->options['notificationsInvitedToChannelSound'] = $notificationsInvitedToChannelSound;
        return $this;
    }

    /**
     * The URL for pre-event webhooks, which are called by using the `webhook_method`. See [Webhook Events](https://www.twilio.com/docs/chat/webhook-events) for more details.
     * 
     * @param string $preWebhookUrl The webhook URL for pre-event webhooks
     * @return $this Fluent Builder
     */
    public function setPreWebhookUrl($preWebhookUrl) {
        $this->options['preWebhookUrl'] = $preWebhookUrl;
        return $this;
    }

    /**
     * The URL for post-event webhooks, which are called by using the `webhook_method`. See [Webhook Events](https://www.twilio.com/docs/chat/webhook-events) for more details.
     * 
     * @param string $postWebhookUrl The URL for post-event webhooks
     * @return $this Fluent Builder
     */
    public function setPostWebhookUrl($postWebhookUrl) {
        $this->options['postWebhookUrl'] = $postWebhookUrl;
        return $this;
    }

    /**
     * The HTTP method to use for calls to the `pre_webhook_url` and `post_webhook_url` webhooks.  Can be: `POST` or `GET` and the default is `POST`. See [Webhook Events](https://www.twilio.com/docs/chat/webhook-events) for more details.
     * 
     * @param string $webhookMethod The HTTP method  to use for both PRE and POST
     *                              webhooks
     * @return $this Fluent Builder
     */
    public function setWebhookMethod($webhookMethod) {
        $this->options['webhookMethod'] = $webhookMethod;
        return $this;
    }

    /**
     * The list of WebHook events that are enabled for this Service instance. See [Webhook Events](https://www.twilio.com/docs/chat/webhook-events) for more details.
     * 
     * @param string $webhookFilters The list of WebHook events that are enabled
     *                               for this Service instance
     * @return $this Fluent Builder
     */
    public function setWebhookFilters($webhookFilters) {
        $this->options['webhookFilters'] = $webhookFilters;
        return $this;
    }

    /**
     * The maximum number of Members that can be added to Channels within this Service. Can be up to 1,000.
     * 
     * @param integer $limitsChannelMembers The maximum number of Members that can
     *                                      be added to Channels within this Service
     * @return $this Fluent Builder
     */
    public function setLimitsChannelMembers($limitsChannelMembers) {
        $this->options['limitsChannelMembers'] = $limitsChannelMembers;
        return $this;
    }

    /**
     * The maximum number of Channels Users can be a Member of within this Service. Can be up to 1,000.
     * 
     * @param integer $limitsUserChannels The maximum number of Channels Users can
     *                                    be a Member of within this Service
     * @return $this Fluent Builder
     */
    public function setLimitsUserChannels($limitsUserChannels) {
        $this->options['limitsUserChannels'] = $limitsUserChannels;
        return $this;
    }

    /**
     * The message to send when a media message has no text. Can be used as placeholder message.
     * 
     * @param string $mediaCompatibilityMessage The message to send when a media
     *                                          message has no text
     * @return $this Fluent Builder
     */
    public function setMediaCompatibilityMessage($mediaCompatibilityMessage) {
        $this->options['mediaCompatibilityMessage'] = $mediaCompatibilityMessage;
        return $this;
    }

    /**
     * The number of times to retry a call to the `pre_webhook_url` if the request times out (after 5 seconds) or it receives a 429, 503, or 504 HTTP response. Default retry count is 0 times, which means the call won't be retried.
     * 
     * @param integer $preWebhookRetryCount Count of times webhook will be retried
     *                                      in case of timeout or 429/503/504 HTTP
     *                                      responses
     * @return $this Fluent Builder
     */
    public function setPreWebhookRetryCount($preWebhookRetryCount) {
        $this->options['preWebhookRetryCount'] = $preWebhookRetryCount;
        return $this;
    }

    /**
     * The number of times to retry a call to the `post_webhook_url` if the request times out (after 5 seconds) or it receives a 429, 503, or 504 HTTP response. The default is 0, which means the call won't be retried.
     * 
     * @param integer $postWebhookRetryCount The number of times calls to the
     *                                       `post_webhook_url` will be retried
     * @return $this Fluent Builder
     */
    public function setPostWebhookRetryCount($postWebhookRetryCount) {
        $this->options['postWebhookRetryCount'] = $postWebhookRetryCount;
        return $this;
    }

    /**
     * Whether to log notifications. Can be: `true` or `false` and the default is `false`.
     * 
     * @param boolean $notificationsLogEnabled Whether to log notifications
     * @return $this Fluent Builder
     */
    public function setNotificationsLogEnabled($notificationsLogEnabled) {
        $this->options['notificationsLogEnabled'] = $notificationsLogEnabled;
        return $this;
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        $options = array();
        foreach ($this->options as $key => $value) {
            if ($value != Values::NONE) {
                $options[] = "$key=$value";
            }
        }
        return '[Twilio.Chat.V2.UpdateServiceOptions ' . implode(' ', $options) . ']';
    }
}