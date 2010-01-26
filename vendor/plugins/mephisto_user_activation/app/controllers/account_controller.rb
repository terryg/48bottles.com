AccountController.class_eval do
  observer :user_observer

  alias __login login

  def login
    l = __login
    if flash[:error]
      if User.require_activation and user = User.authenticate_for(site, params[:login], params[:password], :require_activation=>false)
        flash[:error] = "You must activate your account before you can log in.  An activation code was e-mailed to you when you signed up."
        flash[:user_to_activate] = user
      end
    end
    l
  end

  def signup
    @user = User.new(params[:user])
    return unless request.post?
    @user.save!
    Membership.create(:user_id=>@user.id, :site_id=>site.id)
    self.current_user = @user
    if User.require_activation
      flash[:notice] = "Thanks for signing up!  An activation code has been sent to #{@user.email}"
    else
      flash[:notice] = "Thanks for signing up!"
    end
    redirect_back_or_default(:controller => '/account', :action => 'index')
  rescue ActiveRecord::RecordInvalid
    render :action => 'signup'
  end

  def activate
    if params[:id]
      @user = User.find_by_site_and_activation_code(site, params[:id])
      if @user
        if @user.activated_at
          flash[:notice] = "Your account has already been activated." 
        else
          @user.activate
          flash[:notice] = "Your account has been activated." 
        end
        self.current_user = @user
        redirect_back_or_default(default_url(self.current_user))
      else
        flash[:error] = "Unable to activate the account.  Did you enter the correct information?" 
      end
    end
  end
  
  def send_activation_code
    if request.post? and user = flash[:user_to_activate] 
      UserNotifier.deliver_signup_notification(user)
      flash[:user_to_activate] = nil
      flash[:error] = nil
      flash[:notice] = "An activation code has been sent to #{user.email}"
    end
    redirect_to :controller => '/account', :action => 'login'
  end
end
