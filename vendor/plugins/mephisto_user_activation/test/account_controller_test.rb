ENV['RAILS_ENV'] ||= 'test'
require File.expand_path(File.join(File.dirname(__FILE__), '../../../../config/environment.rb'))
require File.join(RAILS_ROOT,'test','test_helper')
require File.join(File.dirname(__FILE__),'../init.rb')
require_dependency 'account_controller'

# Re-raise errors caught by the controller.
class AccountController; def rescue_action(e) raise e end; end

class AccountControllerTest < Test::Unit::TestCase
  fixtures :users, :sites, :memberships, :contents

  def setup
    monkey_patch
    @controller = AccountController.new
    @request    = ActionController::TestRequest.new
    @response   = ActionController::TestResponse.new
    
    # for testing action mailer
    @emails = ActionMailer::Base.deliveries 
    @emails.clear
  end

  def test_should_login_and_redirect
    post :login, :login => 'quentin', :password => 'quentin'
    assert session[:user]
    # quentin has User.admin true
    assert_redirected_to :controller => 'admin/overview', :action => 'index'

    post :login, :login => 'arthur', :password => 'arthur'
    assert session[:user]
    # arthur is an admin for the site :first
    assert_redirected_to :controller => 'admin/overview', :action => 'index'
    get :logout
    assert !session[:user]

    # (need to activate ben before logging in)
    get :activate, :id => users(:ben).activation_code
    post :login, :login => 'ben', :password => 'arthur'
    assert session[:user]
    # ben is not an admin so should be redirected to the front page
    assert_redirected_to :controller => 'mephisto', :action => 'dispatch'
    get :logout
    assert !session[:user]

    # make sure redirected to referrer
    post :login, :login => 'arthur', :password => 'arthur', :referrer => contents(:welcome).full_permalink
    assert_redirected_to contents(:welcome).full_permalink
    get :logout
    assert !session[:user]
  end

  def test_should_activate_user
    if User.require_activation
      assert_nil User.authenticate_for(sites(:first), 'ben', 'arthur')
      get :activate, :id => users(:ben).activation_code
      assert_equal users(:ben), User.authenticate_for(sites(:first), 'ben', 'arthur')
    end
  end

  def test_should_not_activate_nil
    get :activate, :id => nil
    assert_activate_error
  end

  def test_should_not_activate_bad
    get :activate, :id => 'foobar'
    assert flash.has_key?(:error), "Flash should contain error message." 
    assert_activate_error
  end

  def assert_activate_error
    assert_response :success
    assert_template "account/activate" 
  end

  def test_should_activate_user_and_send_activation_email
    if User::require_activation
      get :activate, :id => users(:ben).activation_code
      assert_equal 1, @emails.length
      assert(@emails.first.subject =~ /Your account has been activated/)
      assert(@emails.first.body    =~ /#{assigns(:user).login}, your account has been activated/)
    end
  end

  def test_should_send_activation_email_after_signup
    if User::require_activation
      user = create_user
      assert_equal 1, @emails.length
      assert(@emails.first.subject =~ /Please activate your new account/)
      assert(@emails.first.body    =~ /Username: quire/)
      assert(@emails.first.body    =~ /Password: quire/)
      assert(@emails.first.body    =~ /account\/activate\/#{user.activation_code}/)
    end
  end

  protected

  def auth_token(token)
    CGI::Cookie.new('name' => 'auth_token', 'value' => token)
  end
  
  def cookie_for(user)
    auth_token users(user).remember_token
  end
  
  def create_user(options = {})
    args = { :login => 'quire', :email => 'quire@example.com', 
      :password => 'quire', :password_confirmation => 'quire' }.merge(options)
    post :signup, :user => args
    User.find_by_login(args[:login])
  end
end
