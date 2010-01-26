UserNotifier.class_eval do

  def signup_notification(user)
    setup_email(user)
    @subject += 'Please activate your new account'
    @body[:url] = url_for :controller => 'account', :action => 'activate', :id => user.activation_code
  end
  
  def activation(user)
    setup_email(user)
    @subject += 'Your account has been activated!'
    @body[:url] = dispatch_url :path => []
  end
end
