UserObserver.class_eval do
  def after_create(user)
    if User.require_activation
      UserNotifier.deliver_signup_notification(user)
    end
  end

  alias __after_save after_save

  def after_save(user)
    if User.require_activation
      UserNotifier.deliver_activation(user) if user.recently_activated?
    end
    __after_save(user)
  end
end
