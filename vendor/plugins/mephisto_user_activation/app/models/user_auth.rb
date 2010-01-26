Dependencies.require_or_load File.join(RAILS_ROOT, 'app', 'models', 'user_auth.rb')

UserAuth.class_eval do
  @@require_activation = true  # Set to true to require activation
  if @@require_activation
    before_create :make_activation_code
    # patch all the subclasses too
    subclasses.each { |s| s.before_create :make_activation_code }
  end
  mattr_reader :require_activation

  # Authenticates a user by their login name and unencrypted password.  Returns the user or nil.
  def self.authenticate_for(site, login, password, options={})
    options[:require_activation] = @@require_activation if options[:require_activation].nil?
    if options[:require_activation]
      activation_cond = ' and activated_at is not NULL'
    else
      activation_cond = ''
    end
    u = find(:first, class_variable_get(:@@membership_options).merge(
      :conditions => ['users.login = ? and (memberships.site_id = ? or users.admin = ?)' + activation_cond, 
                      login, site.id, true]))
    u && u.authenticated?(password) ? u : nil
  end

  def self.find_by_site_and_activation_code(site, activation_code)
    with_deleted_scope do
      find_with_deleted(:first, class_variable_get(:@@membership_options).merge(
      :conditions => ['users.activation_code = ? and memberships.site_id = ?', activation_code, site.id]))
    end
  end

  # Activates the user in the database.
  def activate
    @activated = true
    update_attributes(:activated_at => Time.now.utc)
  end
  
  # Returns true if the user has just been activated.
  def recently_activated?
    @activated
  end

  protected

    def make_activation_code
      self.activation_code = Digest::SHA1.hexdigest( Time.now.to_s.split(//).sort_by {rand}.join )
    end
end
