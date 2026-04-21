package com.jobportal.controller;

import com.jobportal.entity.ApplicationStatus;
import com.jobportal.entity.Role;
import com.jobportal.service.ApplicationService;
import com.jobportal.service.JobService;
import com.jobportal.service.UserService;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/admin")
public class AdminController {
    private final UserService userService;
    private final JobService jobService;
    private final ApplicationService applicationService;

    public AdminController(UserService userService, JobService jobService, ApplicationService applicationService) {
        this.userService = userService;
        this.jobService = jobService;
        this.applicationService = applicationService;
    }

    @GetMapping("/dashboard")
    public String dashboard(Model model) {
        model.addAttribute("students", userService.findAllByRole(Role.STUDENT));
        model.addAttribute("employers", userService.findAllByRole(Role.EMPLOYER));
        model.addAttribute("jobs", jobService.getAllJobs());
        model.addAttribute("appliedCount", applicationService.countApplied());
        model.addAttribute("shortlistedCount", applicationService.countShortlisted());
        model.addAttribute("role", "ADMIN");
        model.addAttribute("pageTitle", "Admin Dashboard");
        return "admin-dashboard-v2";
    }

    @PostMapping("/users/{id}/delete")
    public String deleteUser(@PathVariable Long id) {
        userService.delete(id);
        return "redirect:/admin/dashboard";
    }

    @PostMapping("/jobs/{id}/delete")
    public String deleteJob(@PathVariable Long id) {
        jobService.deleteJob(id, jobService.getById(id).getEmployer());
        return "redirect:/admin/dashboard";
    }
}
