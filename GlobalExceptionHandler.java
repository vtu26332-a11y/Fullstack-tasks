package com.jobportal.controller;

import com.jobportal.entity.ApplicationStatus;
import com.jobportal.entity.Job;
import com.jobportal.entity.User;
import com.jobportal.service.ApplicationService;
import com.jobportal.service.JobService;
import com.jobportal.service.UserService;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.security.Principal;

@Controller
@RequestMapping("/employer")
public class EmployerController {
    private final UserService userService;
    private final JobService jobService;
    private final ApplicationService applicationService;

    public EmployerController(UserService userService, JobService jobService, ApplicationService applicationService) {
        this.userService = userService;
        this.jobService = jobService;
        this.applicationService = applicationService;
    }

    @GetMapping("/dashboard")
    public String dashboard(Model model, Principal principal) {
        User employer = userService.findByEmail(principal.getName());
        model.addAttribute("employer", employer);
        var jobs = jobService.getByEmployer(employer);
        model.addAttribute("jobs", jobs);
        model.addAttribute("newJob", new Job());
        // Simple employer analytics derived from current in-memory dataset.
        long totalApplicants = 0;
        long shortlistedCount = 0;
        for (var job : jobs) {
            var apps = applicationService.byJob(job);
            totalApplicants += apps.size();
            shortlistedCount += apps.stream().filter(a -> a.getStatus() == com.jobportal.entity.ApplicationStatus.SHORTLISTED).count();
        }
        model.addAttribute("jobsCount", jobs.size());
        model.addAttribute("totalApplicants", totalApplicants);
        model.addAttribute("shortlistedCount", shortlistedCount);
        model.addAttribute("role", "EMPLOYER");
        model.addAttribute("pageTitle", "Employer Dashboard");
        return "employer-dashboard-v2";
    }

    @PostMapping("/jobs")
    public String addJob(@ModelAttribute Job job, Principal principal) {
        User employer = userService.findByEmail(principal.getName());
        jobService.postJob(job, employer);
        return "redirect:/employer/dashboard";
    }

    @PostMapping("/jobs/{id}/delete")
    public String deleteJob(@PathVariable Long id, Principal principal) {
        User employer = userService.findByEmail(principal.getName());
        jobService.deleteJob(id, employer);
        return "redirect:/employer/dashboard";
    }

    @GetMapping("/jobs/{id}/applicants")
    public String applicants(@PathVariable Long id, Model model) {
        Job job = jobService.getById(id);
        model.addAttribute("job", job);
        model.addAttribute("applications", applicationService.byJob(job));
        model.addAttribute("role", "EMPLOYER");
        model.addAttribute("pageTitle", "Applicants - " + job.getTitle());
        return "applicants-v2";
    }

    @PostMapping("/applications/{id}/status")
    public String status(@PathVariable Long id, @RequestParam ApplicationStatus status) {
        applicationService.updateStatus(id, status);
        return "redirect:/employer/dashboard";
    }
}
